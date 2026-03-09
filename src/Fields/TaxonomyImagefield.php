<?php

namespace Yankov\MetaFieldsBuilder\Fields;

/**
 * Adds an image upload field to taxonomy term add/edit screens.
 *
 * Stores the WordPress attachment ID as term meta.
 * Self-contained — call register() and it hooks into the right actions.
 *
 * Usage:
 *   (new TaxonomyImageField('genre'))->register();
 *
 * Retrieve the stored image:
 *   $imageId = get_term_meta($term_id, 'genre_image_id', true);
 *
 * @since 1.3.0
 */
class TaxonomyImageField
{
    private string $taxonomy;
    private string $metaKey;

    public function __construct(string $taxonomy)
    {
        $this->taxonomy = $taxonomy;
        $this->metaKey  = $taxonomy . '_image_id';
    }

    public function getMetaKey(): string
    {
        return $this->metaKey;
    }

    public function register(): void
    {
        add_action($this->taxonomy . '_add_form_fields', [$this, 'renderAddField']);
        add_action($this->taxonomy . '_edit_form_fields', [$this, 'renderEditField']);
        add_action('created_' . $this->taxonomy, [$this, 'save']);
        add_action('edited_' . $this->taxonomy, [$this, 'save']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueMedia']);
    }

    public function enqueueMedia(string $hook): void
    {
        if ($hook !== 'edit-tags.php' && $hook !== 'term.php') {
            return;
        }

        $screen = get_current_screen();
        if ($screen && $screen->taxonomy === $this->taxonomy) {
            wp_enqueue_media();
        }
    }

    public function renderAddField(): void
    {
        ?>
        <div class="form-field">
            <label><?php echo esc_html__('Image', 'meta-fields-builder'); ?></label>
            <div id="<?php echo esc_attr($this->metaKey); ?>_preview"></div>
            <input type="hidden" name="<?php echo esc_attr($this->metaKey); ?>" id="<?php echo esc_attr($this->metaKey); ?>" value="">
            <button type="button" class="button" id="<?php echo esc_attr($this->metaKey); ?>_upload">
                <?php echo esc_html__('Select Image', 'meta-fields-builder'); ?>
            </button>
            <button type="button" class="button" id="<?php echo esc_attr($this->metaKey); ?>_remove" style="display:none;">
                <?php echo esc_html__('Remove', 'meta-fields-builder'); ?>
            </button>
            <?php $this->renderScript(); ?>
        </div>
        <?php
    }

    public function renderEditField(\WP_Term $term): void
    {
        $imageId  = (int) get_term_meta($term->term_id, $this->metaKey, true);
        $imageUrl = $imageId ? wp_get_attachment_image_url($imageId, 'thumbnail') : '';
        ?>
        <tr class="form-field">
            <th scope="row"><label><?php echo esc_html__('Image', 'meta-fields-builder'); ?></label></th>
            <td>
                <div id="<?php echo esc_attr($this->metaKey); ?>_preview">
                    <?php if ($imageUrl): ?>
                        <img src="<?php echo esc_url($imageUrl); ?>" style="max-width:150px;height:auto;">
                    <?php endif; ?>
                </div>
                <input type="hidden" name="<?php echo esc_attr($this->metaKey); ?>" id="<?php echo esc_attr($this->metaKey); ?>" value="<?php echo esc_attr((string) $imageId); ?>">
                <button type="button" class="button" id="<?php echo esc_attr($this->metaKey); ?>_upload">
                    <?php echo esc_html__('Select Image', 'meta-fields-builder'); ?>
                </button>
                <button type="button" class="button" id="<?php echo esc_attr($this->metaKey); ?>_remove" style="<?php echo $imageId ? '' : 'display:none;'; ?>">
                    <?php echo esc_html__('Remove', 'meta-fields-builder'); ?>
                </button>
                <?php $this->renderScript(); ?>
            </td>
        </tr>
        <?php
    }

    public function save(int $termId): void
    {
        if (!isset($_POST[$this->metaKey])) {
            return;
        }

        $imageId = absint($_POST[$this->metaKey]);

        if ($imageId) {
            update_term_meta($termId, $this->metaKey, $imageId);
        } else {
            delete_term_meta($termId, $this->metaKey);
        }
    }

    private function renderScript(): void
    {
        $key = esc_js($this->metaKey);
        ?>
        <script>
        (function(){
            var input   = document.getElementById('<?php echo $key; ?>');
            var preview = document.getElementById('<?php echo $key; ?>_preview');
            var upload  = document.getElementById('<?php echo $key; ?>_upload');
            var remove  = document.getElementById('<?php echo $key; ?>_remove');

            upload.addEventListener('click', function(e){
                e.preventDefault();
                var frame = wp.media({ multiple: false });
                frame.on('select', function(){
                    var attachment = frame.state().get('selection').first().toJSON();
                    var url = attachment.sizes && attachment.sizes.thumbnail
                        ? attachment.sizes.thumbnail.url
                        : attachment.url;
                    input.value = attachment.id;
                    preview.innerHTML = '<img src="' + url + '" style="max-width:150px;height:auto;">';
                    remove.style.display = '';
                });
                frame.open();
            });

            remove.addEventListener('click', function(e){
                e.preventDefault();
                input.value = '';
                preview.innerHTML = '';
                remove.style.display = 'none';
            });
        })();
        </script>
        <?php
    }
}
