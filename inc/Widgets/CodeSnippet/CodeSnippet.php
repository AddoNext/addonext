<?php
namespace Elementrify\Widgets\CodeSnippet;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class CodeSnippet extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-code elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-code-snippet';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Code Snippet', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Highlight code blocks with editor-style window', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#282c34';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-code-snippet'];
    }

    public function get_script_depends(): array
    {
        return ['elementrify-code-snippet'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-code-snippet',
            ELEMENTRIFY_URL . 'assets/css/code-snippet.css',
            [],
            ELEMENTRIFY_VERSION
        );
        wp_register_script(
            'elementrify-code-snippet',
            ELEMENTRIFY_URL . 'assets/js/code-snippet.js',
            [],
            ELEMENTRIFY_VERSION,
            true
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'elementrify'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('filename', [
            'label'   => __('File name', 'elementrify'),
            'type'    => Controls_Manager::TEXT,
            'default' => 'main.js',
            'placeholder' => 'main.js',
        ]);

        $this->add_control('language', [
            'label'   => __('Language', 'elementrify'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'javascript',
            'options' => [
                'javascript' => __('JavaScript', 'elementrify'),
                'php'        => __('PHP', 'elementrify'),
                'css'        => __('CSS', 'elementrify'),
                'html'       => __('HTML', 'elementrify'),
                'python'     => __('Python', 'elementrify'),
                'plain'      => __('Plain text', 'elementrify'),
            ],
        ]);

        $default_code = "function calculateTotal(items) {\n  let total = 0;\n\n  for (let i = 0; i < items.length; i++) {\n    total += items[i].price;\n  }\n\n  return total;\n}";

        $this->add_control('code', [
            'label'   => __('Code', 'elementrify'),
            'type'    => Controls_Manager::CODE,
            'default' => $default_code,
        ]);

        $this->end_controls_section();
    }

    public static function highlight_code(string $code, string $language): string
    {
        $plain = '<span class="elementrify-cs_plain">' . esc_html($code) . '</span>';
        if ($code === '') {
            return '';
        }
        if ($language === 'plain') {
            return $plain;
        }
        if ($language === 'javascript') {
            $out = self::highlight_javascript($code);
            return $out !== '' ? $out : $plain;
        }
        if (in_array($language, ['php', 'css', 'html', 'python'], true)) {
            $out = self::highlight_generic($code, $language);
            return $out !== '' ? $out : $plain;
        }
        return $plain;
    }

    private static function highlight_javascript(string $code): string
    {
        $keywords = [
            'function', 'return', 'let', 'const', 'var', 'for', 'if', 'else', 'while', 'do',
            'switch', 'case', 'break', 'default', 'try', 'catch', 'finally', 'throw', 'new',
            'typeof', 'instanceof', 'in', 'of', 'true', 'false', 'null', 'undefined', 'class',
            'extends', 'import', 'export', 'from', 'async', 'await', 'static', 'get', 'set',
        ];
        $builtins = ['length', 'push', 'pop', 'log', 'parseInt', 'parseFloat', 'toString'];
        $escaped = esc_html($code);
        $escaped = preg_replace_callback(
            '/(["\'])(?:(?!\1)[^\\\\]|\\\\.)*\1/',
            function ($m) {
                return '<span class="elementrify-cs_string">' . $m[0] . '</span>';
            },
            $escaped
        );
        $escaped = preg_replace('/\/\/[^\n]*/', '<span class="elementrify-cs_comment">$0</span>', $escaped);
        $escaped = preg_replace('/\/\*[\s\S]*?\*\//', '<span class="elementrify-cs_comment">$0</span>', $escaped);
        $escaped = preg_replace('/\b(\d+\.?\d*)\b/', '<span class="elementrify-cs_number">$1</span>', $escaped);
        $kw_pattern = '/\b(' . implode('|', array_map('preg_quote', $keywords)) . ')\b/';
        $escaped = preg_replace($kw_pattern, '<span class="elementrify-cs_keyword">$1</span>', $escaped);
        $bi_pattern = '/\b(' . implode('|', array_map('preg_quote', $builtins)) . ')\b/';
        $escaped = preg_replace($bi_pattern, '<span class="elementrify-cs_builtin">$1</span>', $escaped);
        $escaped = preg_replace(
            '/\b([a-zA-Z_$][a-zA-Z0-9_$]*)\s*(?=\()/',
            '<span class="elementrify-cs_funct">$1</span>',
            $escaped
        );
        $not_kw_bi = array_merge($keywords, $builtins);
        $exclude_parts = array_map(function ($w) {
            return preg_quote($w, '/') . '\b';
        }, $not_kw_bi);
        $exclude = '/\b(?!' . implode('|', $exclude_parts) . ')([a-zA-Z_$][a-zA-Z0-9_$]*)\b/';
        $escaped = preg_replace($exclude, '<span class="elementrify-cs_identifier">$1</span>', $escaped);
        return $escaped;
    }

    private static function highlight_generic(string $code, string $language): string
    {
        $escaped = esc_html($code);
        if ($language === 'php') {
            $php_keywords = ['function', 'return', 'echo', 'if', 'else', 'foreach', 'for', 'while', 'true', 'false', 'null', 'array', 'new', 'class', 'extends', 'public', 'private', 'protected', 'static', 'const', 'use', 'namespace', 'require', 'include'];
            $pattern = '/\b(' . implode('|', array_map('preg_quote', $php_keywords)) . ')\b/';
            $escaped = preg_replace($pattern, '<span class="elementrify-cs_keyword">$1</span>', $escaped);
        }
        if ($language === 'css') {
            $escaped = preg_replace('/([{}:;,])/', '<span class="elementrify-cs_punctuation">$1</span>', $escaped);
            $escaped = preg_replace('/\b([a-z-]+)\s*(?={)/', '<span class="elementrify-cs_selector">$1</span>', $escaped);
        }
        if ($language === 'html') {
            $escaped = preg_replace('/&lt;(\/?)([a-zA-Z0-9-]+)/', '&lt;$1<span class="elementrify-cs_tag">$2</span>', $escaped);
        }
        if ($language === 'python') {
            $py_keywords = ['def', 'return', 'if', 'else', 'elif', 'for', 'while', 'in', 'True', 'False', 'None', 'class', 'import', 'from', 'try', 'except', 'finally', 'with', 'as', 'lambda', 'and', 'or', 'not'];
            $pattern = '/\b(' . implode('|', array_map('preg_quote', $py_keywords)) . ')\b/';
            $escaped = preg_replace($pattern, '<span class="elementrify-cs_keyword">$1</span>', $escaped);
        }
        if (strpos($escaped, '<span') === false) {
            return '<span class="elementrify-cs_plain">' . $escaped . '</span>';
        }
        return $escaped;
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/code-snippet/code-snippet.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var filename = settings.filename || 'main.js';
        var lang = settings.language || 'javascript';
        var code = settings.code || '';
        #>
        <div class="elementrify-code-snippet elementrify-code-snippet--theme-dark">
            <div class="elementrify-code-snippet__header">
                <div class="elementrify-code-snippet__dots">
                    <span class="elementrify-code-snippet__dot elementrify-code-snippet__dot--red"></span>
                    <span class="elementrify-code-snippet__dot elementrify-code-snippet__dot--yellow"></span>
                    <span class="elementrify-code-snippet__dot elementrify-code-snippet__dot--green"></span>
                </div>
                <div class="elementrify-code-snippet__header-right">
                    <button type="button" class="elementrify-code-snippet__copy" aria-label="<?php echo esc_attr__('Copy code', 'elementrify'); ?>">
                    <span class="elementrify-code-snippet__copy-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></span>
                    <span class="elementrify-code-snippet__check-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>
                </button>
                    <span class="elementrify-code-snippet__filename">{{ filename }}</span>
                </div>
            </div>
            <pre class="elementrify-code-snippet__pre">
                <code class="elementrify-code-snippet__code" data-language="{{ lang }}">{{ code }}</code>
                <div class="elementrify-code-snippet__display" aria-hidden="true"></div>
            </pre>
        </div>
        <?php
    }
}
