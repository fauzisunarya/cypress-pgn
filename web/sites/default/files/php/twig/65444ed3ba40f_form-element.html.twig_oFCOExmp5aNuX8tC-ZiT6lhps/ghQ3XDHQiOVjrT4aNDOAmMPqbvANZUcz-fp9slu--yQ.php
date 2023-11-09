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

/* themes/custom/telkom_cms/templates/form/form-element.html.twig */
class __TwigTemplate_0d745dbb5e23323e9151c7ec301559ab extends \Twig\Template
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
        // line 48
        $context["classes"] = [0 => "form-group", 1 => (((        // line 50
($context["is_landingform"] ?? null) && (($context["title_display"] ?? null) == "invisible"))) ? ("row") : ("col-md-12")), 2 => ((((        // line 51
($context["name"] ?? null) == "name") && (($context["type"] ?? null) == "textfield"))) ? ("form-email") : (""))];
        // line 55
        $context["description_classes"] = [0 => "w3-small", 1 => "w3-row", 2 => "description", 3 => (((        // line 59
($context["description_display"] ?? null) == "invisible")) ? ("visually-hidden") : (""))];
        // line 62
        echo "<div";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null)], "method", false, false, true, 62), 62, $this->source), "html", null, true);
        echo ">
  <div class=\"";
        // line 63
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($context["is_landingform"] ?? null) && (($context["title_display"] ?? null) == "invisible"))) ? ("col-md-11") : ("")));
        echo "\">
    ";
        // line 64
        if (twig_in_filter(($context["label_display"] ?? null), [0 => "before", 1 => "invisible"])) {
            // line 65
            echo "      ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["label"] ?? null), 65, $this->source), "html", null, true);
            echo "
    ";
        }
        // line 67
        echo "
    ";
        // line 68
        if ( !twig_test_empty(($context["prefix"] ?? null))) {
            // line 69
            echo "      <span class=\"field-prefix\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["prefix"] ?? null), 69, $this->source), "html", null, true);
            echo "</span>
    ";
        }
        // line 71
        echo "
    ";
        // line 72
        if (((($context["description_display"] ?? null) == "before") && twig_get_attribute($this->env, $this->source, ($context["description"] ?? null), "content", [], "any", false, false, true, 72))) {
            // line 73
            echo "      <div";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["description"] ?? null), "attributes", [], "any", false, false, true, 73), 73, $this->source), "html", null, true);
            echo ">
        ";
            // line 74
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["description"] ?? null), "content", [], "any", false, false, true, 74), 74, $this->source), "html", null, true);
            echo "
      </div>
    ";
        }
        // line 77
        echo "
    ";
        // line 78
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["children"] ?? null), 78, $this->source), "html", null, true);
        echo "

    ";
        // line 80
        if ( !twig_test_empty(($context["suffix"] ?? null))) {
            // line 81
            echo "      <span class=\"field-suffix\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["suffix"] ?? null), 81, $this->source), "html", null, true);
            echo "</span>
    ";
        }
        // line 83
        echo "
    ";
        // line 84
        if ((($context["label_display"] ?? null) == "after")) {
            // line 85
            echo "      ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["label"] ?? null), 85, $this->source), "html", null, true);
            echo "
    ";
        }
        // line 87
        echo "
    ";
        // line 88
        if (($context["errors"] ?? null)) {
            // line 89
            echo "      <div class=\"w3-panel w3-pale-red w3-leftbar w3-border w3-border-red form-item--error-message\">
        <strong>";
            // line 90
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["errors"] ?? null), 90, $this->source), "html", null, true);
            echo "</strong>
      </div>
    ";
        }
        // line 93
        echo "  </div>

  ";
        // line 95
        if ((($context["is_landingform"] ?? null) && (($context["title_display"] ?? null) == "invisible"))) {
            // line 96
            echo "  <div class=\"col-md-1 mt-1\">
    <button type=\"button\" class=\"btn btn-danger rounded btn-deleterow\"><i class=\"bi bi-trash\"></i></button>
  </div>
  ";
        }
        // line 100
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "themes/custom/telkom_cms/templates/form/form-element.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  143 => 100,  137 => 96,  135 => 95,  131 => 93,  125 => 90,  122 => 89,  120 => 88,  117 => 87,  111 => 85,  109 => 84,  106 => 83,  100 => 81,  98 => 80,  93 => 78,  90 => 77,  84 => 74,  79 => 73,  77 => 72,  74 => 71,  68 => 69,  66 => 68,  63 => 67,  57 => 65,  55 => 64,  51 => 63,  46 => 62,  44 => 59,  43 => 55,  41 => 51,  40 => 50,  39 => 48,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/custom/telkom_cms/templates/form/form-element.html.twig", "C:\\laragon\\www\\cms\\web\\themes\\custom\\telkom_cms\\templates\\form\\form-element.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 48, "if" => 64);
        static $filters = array("escape" => 62);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if'],
                ['escape'],
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
