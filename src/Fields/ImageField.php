<?php

namespace Yankov\MetaFieldsBuilder\Fields;

/**
 * Renders an image upload field using the WordPress media library.
 *
 * Stores the attachment ID as post meta.
 *
 * @since 1.1.0
 */
class ImageField implements Field
{
    public function __construct(
        public readonly string $name,
        public readonly string $label
    ) {}

    /**
     * Render the field.
     *
     * @param int $post_id
     * @return void
     */
    public function render(int $post_id)
    {
        $attachment_id = get_post_meta($post_id, $this->name, true);
        $image_url = $attachment_id ? wp_get_attachment_image_url($attachment_id, 'thumbnail') : '';
        $display = $image_url ? 'block' : 'none';

        wp_enqueue_media();

        echo "
        <div id=\"field_id_{$this->name}\" style=\"display:block;margin-bottom:8px;\">
            <label style=\"display:block;margin-bottom:4px;\">{$this->label}:</label>
            <div id=\"{$this->name}_preview\" style=\"display:{$display};margin-bottom:8px;\">
                <img src=\"{$image_url}\" style=\"max-width:150px;height:auto;\" />
            </div>
            <input type=\"hidden\" name=\"{$this->name}\" id=\"{$this->name}_input\" value=\"{$attachment_id}\" />
            <button type=\"button\" class=\"button\" id=\"{$this->name}_upload\">Select Image</button>
            <button type=\"button\" class=\"button\" id=\"{$this->name}_remove\" style=\"display:{$display};\">Remove</button>
            <script>
            (function() {
                var uploadBtn = document.getElementById('{$this->name}_upload');
                var removeBtn = document.getElementById('{$this->name}_remove');
                var input     = document.getElementById('{$this->name}_input');
                var preview   = document.getElementById('{$this->name}_preview');

                uploadBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    var frame = wp.media({ multiple: false });
                    frame.on('select', function() {
                        var attachment = frame.state().get('selection').first().toJSON();
                        input.value = attachment.id;
                        preview.innerHTML = '<img src=\"' + attachment.sizes.thumbnail.url + '\" style=\"max-width:150px;height:auto;\" />';
                        preview.style.display = 'block';
                        removeBtn.style.display = 'inline-block';
                    });
                    frame.open();
                });

                removeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    input.value = '';
                    preview.innerHTML = '';
                    preview.style.display = 'none';
                    removeBtn.style.display = 'none';
                });
            })();
            </script>
        </div>";
    }

    /**
     * Save the field data into the database table.
     *
     * @param int $post_id
     * @return void
     */
    public function save(int $post_id)
    {
        if (isset($_POST[$this->name])) {
            $attachment_id = absint($_POST[$this->name]);
            update_post_meta($post_id, $this->name, $attachment_id ?: '');
        }
    }
}
