<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* themes/custom/telkom_cms/templates/region/content/views/views-view.html.twig */
class __TwigTemplate_bba3f7f6da00b90272cd76b69983031a extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 33
        echo "
";
        // line 35
        $context["classes"] = [0 => "w3-row", 1 => "view", 2 => ("view-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(        // line 38
($context["id"] ?? null), 38, $this->source))), 3 => ("view-id-" . $this->sandbox->ensureToStringAllowed(        // line 39
($context["id"] ?? null), 39, $this->source)), 4 => ("view-display-id-" . $this->sandbox->ensureToStringAllowed(        // line 40
($context["display_id"] ?? null), 40, $this->source)), 5 => ((        // line 41
($context["dom_id"] ?? null)) ? (("js-view-dom-id-" . $this->sandbox->ensureToStringAllowed(($context["dom_id"] ?? null), 41, $this->source))) : ("")), 6 => (((        // line 42
($context["is_detail_paket_page"] ?? null) && ($context["empty_rows"] ?? null))) ? ("hidden") : (""))];
        // line 45
        echo "<div";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null)], "method", false, false, true, 45), 45, $this->source), "html", null, true);
        echo ">
  ";
        // line 46
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_prefix"] ?? null), 46, $this->source), "html", null, true);
        echo "
  ";
        // line 47
        if (($context["title"] ?? null)) {
            // line 48
            echo "    ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null), 48, $this->source), "html", null, true);
            echo "
  ";
        }
        // line 50
        echo "  ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_suffix"] ?? null), 50, $this->source), "html", null, true);
        echo "
  ";
        // line 51
        if ((($context["header"] ?? null) && ($context["show_header"] ?? null))) {
            // line 52
            echo "    <header class=\"view-header\">
      ";
            // line 53
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["header"] ?? null), 53, $this->source), "html", null, true);
            echo "
    </header>
  ";
        }
        // line 56
        echo "  ";
        if (($context["exposed"] ?? null)) {
            // line 57
            echo "    <div class=\"view-filters form-group\">
      ";
            // line 58
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["exposed"] ?? null), 58, $this->source), "html", null, true);
            echo "
    </div>
  ";
        }
        // line 61
        echo "  ";
        if (($context["attachment_before"] ?? null)) {
            // line 62
            echo "    <div class=\"attachment attachment-before\">
      ";
            // line 63
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["attachment_before"] ?? null), 63, $this->source), "html", null, true);
            echo "
    </div>
  ";
        }
        // line 66
        echo "
  ";
        // line 67
        if (($context["rows"] ?? null)) {
            // line 68
            echo "    <div class=\"view-content\">
      ";
            // line 69
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["rows"] ?? null), 69, $this->source), "html", null, true);
            echo "
    </div>
  ";
        } elseif (        // line 71
($context["empty"] ?? null)) {
            // line 72
            echo "    <div class=\"view-empty\">
      ";
            // line 73
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["empty"] ?? null), 73, $this->source), "html", null, true);
            echo "
    </div>
  ";
        }
        // line 76
        echo "
  ";
        // line 77
        if (($context["pager"] ?? null)) {
            // line 78
            echo "    ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["pager"] ?? null), 78, $this->source), "html", null, true);
            echo "
  ";
        }
        // line 80
        echo "  ";
        if (($context["attachment_after"] ?? null)) {
            // line 81
            echo "    <div class=\"attachment attachment-after\">
      ";
            // line 82
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["attachment_after"] ?? null), 82, $this->source), "html", null, true);
            echo "
    </div>
  ";
        }
        // line 85
        echo "  ";
        if (($context["more"] ?? null)) {
            // line 86
            echo "    ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["more"] ?? null), 86, $this->source), "html", null, true);
            echo "
  ";
        }
        // line 88
        echo "  ";
        if (($context["footer"] ?? null)) {
            // line 89
            echo "    <footer class=\"view-footer\">
      ";
            // line 90
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["footer"] ?? null), 90, $this->source), "html", null, true);
            echo "
    </footer>
  ";
        }
        // line 93
        echo "  ";
        if (($context["feed_icons"] ?? null)) {
            // line 94
            echo "    <div class=\"feed-icons\">
      ";
            // line 95
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["feed_icons"] ?? null), 95, $this->source), "html", null, true);
            echo "
    </div>
  ";
        }
        // line 98
        echo "</div>

";
        // line 101
        if ((($context["is_detail_paket_page"] ?? null) && ($context["empty_rows"] ?? null))) {
            // line 102
            echo "  <div class=\"table-responsive\" style='padding-bottom:50px'>
    <table class=\"table\">
        <thead>
            <tr>
                <th>No</th>
                <th>Title</th>
                ";
            // line 109
            echo "                <th class=\"text-center\">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class=\"text-center\" colspan=\"3\">There is no data</td>
            </tr>
        </tbody>
    </table>
  </div>
";
        }
    }

    public function getTemplateName()
    {
        return "themes/custom/telkom_cms/templates/region/content/views/views-view.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  200 => 109,  192 => 102,  190 => 101,  186 => 98,  180 => 95,  177 => 94,  174 => 93,  168 => 90,  165 => 89,  162 => 88,  156 => 86,  153 => 85,  147 => 82,  144 => 81,  141 => 80,  135 => 78,  133 => 77,  130 => 76,  124 => 73,  121 => 72,  119 => 71,  114 => 69,  111 => 68,  109 => 67,  106 => 66,  100 => 63,  97 => 62,  94 => 61,  88 => 58,  85 => 57,  82 => 56,  76 => 53,  73 => 52,  71 => 51,  66 => 50,  60 => 48,  58 => 47,  54 => 46,  49 => 45,  47 => 42,  46 => 41,  45 => 40,  44 => 39,  43 => 38,  42 => 35,  39 => 33,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/custom/telkom_cms/templates/region/content/views/views-view.html.twig", "C:\\laragon\\www\\content-door\\content-management-service\\web\\themes\\custom\\telkom_cms\\templates\\region\\content\\views\\views-view.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 35, "if" => 47);
        static $filters = array("clean_class" => 38, "escape" => 45);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if'],
                ['clean_class', 'escape'],
                []
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
