<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><pre class="elementrify-code-snippet"><code><?php echo esc_html($s['code'] ?? ''); ?></code></pre>
