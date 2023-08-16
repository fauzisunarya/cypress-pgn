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

/* themes/custom/telkom_cms/templates/content/node.html.twig */
class __TwigTemplate_ec28ce1d7fc58126e6b825eb8a2cb797 extends \Twig\Template
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
        // line 71
        $context["classes"] = [0 => "w3-row", 1 => "node", 2 => ("node--type-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source,         // line 74
($context["node"] ?? null), "bundle", [], "any", false, false, true, 74), 74, $this->source))), 3 => ((twig_get_attribute($this->env, $this->source,         // line 75
($context["node"] ?? null), "isPromoted", [], "method", false, false, true, 75)) ? ("node--promoted") : ("")), 4 => ((twig_get_attribute($this->env, $this->source,         // line 76
($context["node"] ?? null), "isSticky", [], "method", false, false, true, 76)) ? ("node--sticky") : ("")), 5 => (( !twig_get_attribute($this->env, $this->source,         // line 77
($context["node"] ?? null), "isPublished", [], "method", false, false, true, 77)) ? ("node--unpublished") : ("")), 6 => ((        // line 78
($context["view_mode"] ?? null)) ? (("node--view-mode-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(($context["view_mode"] ?? null), 78, $this->source)))) : (""))];
        // line 81
        echo "
<article";
        // line 82
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null)], "method", false, false, true, 82), 82, $this->source), "html", null, true);
        echo ">

  ";
        // line 87
        echo "  ";
        if (($context["is_not_approved"] ?? null)) {
            // line 88
            echo "    <div class=\"approval-form\">
      ";
            // line 89
            if (($context["is_rejected"] ?? null)) {
                // line 90
                echo "        <div class=\"rejected\">
            <h6>This content has been rejected</h6>
        </div>
      ";
            } else {
                // line 94
                echo "        <div class=\"approved\">
            <h6>This content was not approved</h6>
        </div>
      ";
            }
            // line 98
            echo "    </div>
  ";
        }
        // line 100
        echo "
  <div class=\"w3-row node__header\">
    ";
        // line 102
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_prefix"] ?? null), 102, $this->source), "html", null, true);
        echo "
    ";
        // line 103
        if ((($context["label"] ?? null) &&  !($context["page"] ?? null))) {
            // line 104
            echo "      <h3 ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["title_attributes"] ?? null), "addClass", [0 => "node__title"], "method", false, false, true, 104), 104, $this->source), "html", null, true);
            echo ">
        <a href=\"";
            // line 105
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["url"] ?? null), 105, $this->source), "html", null, true);
            echo "\" rel=\"bookmark\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["label"] ?? null), 105, $this->source), "html", null, true);
            echo "</a>
      </h3>
    ";
        } elseif (        // line 107
($context["label"] ?? null)) {
            // line 108
            echo "      <h3 ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["title_attributes"] ?? null), "addClass", [0 => "node__title"], "method", false, false, true, 108), 108, $this->source), "html", null, true);
            echo ">
        ";
            // line 109
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["label"] ?? null), 109, $this->source), "html", null, true);
            echo "
      </h3>
    ";
        }
        // line 112
        echo "    ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_suffix"] ?? null), 112, $this->source), "html", null, true);
        echo "

    ";
        // line 114
        if (($context["display_submitted"] ?? null)) {
            // line 115
            echo "      <div class=\"node__meta\">
      ";
            // line 116
            if (($context["author_picture"] ?? null)) {
                // line 117
                echo "        <div class=\"node__author-image\">
          ";
                // line 118
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["author_picture"] ?? null), 118, $this->source), "html", null, true);
                echo "
        </div>
      ";
            }
            // line 121
            echo "        <div class=\"node__author-info\" ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["author_attributes"] ?? null), 121, $this->source), "html", null, true);
            echo ">
          <span ";
            // line 122
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["author_attributes"] ?? null), 122, $this->source), "html", null, true);
            echo ">
            ";
            // line 123
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("By"));
            echo " ";
            ob_start(function () { return ''; });
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["author_name"] ?? null), 123, $this->source), "html", null, true);
            $___internal_parse_0_ = ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(twig_spaceless($___internal_parse_0_));
            echo " | ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["date"] ?? null), 123, $this->source), "html", null, true);
            echo "
          </span>
        </div>
        ";
            // line 126
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["metadata"] ?? null), 126, $this->source), "html", null, true);
            echo "
      </div>
    ";
        }
        // line 129
        echo "  </div>
  ";
        // line 130
        if ((($context["is_front"] ?? null) == true)) {
            // line 131
            echo "  <div";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["content_attributes"] ?? null), "addClass", [0 => "w3-row node__content"], "method", false, false, true, 131), 131, $this->source), "html", null, true);
            echo ">
  ";
        } else {
            // line 133
            echo "  <div";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["content_attributes"] ?? null), "addClass", [0 => "w3-row node__content row flex-row-reverse"], "method", false, false, true, 133), 133, $this->source), "html", null, true);
            echo ">
  ";
        }
        // line 135
        echo "    ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content"] ?? null), 135, $this->source), "html", null, true);
        echo "
  </div>

</article>
";
    }

    public function getTemplateName()
    {
        return "themes/custom/telkom_cms/templates/content/node.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  180 => 135,  174 => 133,  168 => 131,  166 => 130,  163 => 129,  157 => 126,  144 => 123,  140 => 122,  135 => 121,  129 => 118,  126 => 117,  124 => 116,  121 => 115,  119 => 114,  113 => 112,  107 => 109,  102 => 108,  100 => 107,  93 => 105,  88 => 104,  86 => 103,  82 => 102,  78 => 100,  74 => 98,  68 => 94,  62 => 90,  60 => 89,  57 => 88,  54 => 87,  49 => 82,  46 => 81,  44 => 78,  43 => 77,  42 => 76,  41 => 75,  40 => 74,  39 => 71,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/custom/telkom_cms/templates/content/node.html.twig", "C:\\laragon\\www\\content-door\\content-management-service\\web\\themes\\custom\\telkom_cms\\templates\\content\\node.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 71, "if" => 87, "apply" => 123);
        static $filters = array("clean_class" => 74, "escape" => 82, "t" => 123, "spaceless" => 123);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if', 'apply'],
                ['clean_class', 'escape', 't', 'spaceless'],
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
