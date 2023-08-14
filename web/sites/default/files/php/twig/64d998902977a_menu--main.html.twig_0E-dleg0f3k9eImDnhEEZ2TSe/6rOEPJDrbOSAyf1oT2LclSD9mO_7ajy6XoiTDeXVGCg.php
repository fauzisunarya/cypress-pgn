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

/* themes/custom/telkom_cms/templates/region/sidebar_menu/menu--main.html.twig */
class __TwigTemplate_32b86d3a2baa5f9eaf7aac473dff7dad extends \Twig\Template
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
        // line 21
        echo "
<!-- sidebar start -->
<div class=\"sidebar\" id=\"sidebar-wrapper\">
  <a href=\"/\" class=\"sidebar-brand d-flex justify-content-center link-dark text-decoration-none\">
    ";
        // line 25
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, Drupal\twig_tweak\TwigTweakExtension::drupalRegion("sidebar_logo"), "html", null, true);
        echo "
  </a>
  <!-- menu start -->
  <ul class=\"nav nav-pills nav-flush mb-auto text-center d-block scrollarea\">
    ";
        // line 29
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["items"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["menu_parent"]) {
            // line 30
            echo "
      ";
            // line 31
            if (twig_get_attribute($this->env, $this->source, $context["menu_parent"], "below", [], "any", false, false, true, 31)) {
                // line 32
                echo "      <li class=\"nav-item ";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((twig_get_attribute($this->env, $this->source, $context["menu_parent"], "in_active_trail", [], "any", false, false, true, 32)) ? ("active") : ("")));
                echo "\" onmouseover=\"showMenu('";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["menu_parent"], "menu_id", [], "any", false, false, true, 32), 32, $this->source), "html", null, true);
                echo "')\" onmouseleave=\"hideMenu('";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["menu_parent"], "menu_id", [], "any", false, false, true, 32), 32, $this->source), "html", null, true);
                echo "')\">
        <a href=\"#\" class=\"nav-link py-3 collapsed\" data-bs-toggle=\"collapse\" data-bs-target=\"#";
                // line 33
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["menu_parent"], "mobile_menu_id", [], "any", false, false, true, 33), 33, $this->source), "html", null, true);
                echo "\" aria-current=\"page\">
      ";
            } else {
                // line 35
                echo "      <li class=\"nav-item ";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((twig_get_attribute($this->env, $this->source, $context["menu_parent"], "in_active_trail", [], "any", false, false, true, 35)) ? ("active") : ("")));
                echo "\" onclick=\"(function(){window.location.href=`";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["menu_parent"], "url", [], "any", false, false, true, 35), 35, $this->source), "html", null, true);
                echo "`;return false;})();return false;\">
        <a href=\"";
                // line 36
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["menu_parent"], "url", [], "any", false, false, true, 36), 36, $this->source), "html", null, true);
                echo "\" class=\"nav-link py-3\">
      ";
            }
            // line 38
            echo "
          <img src=\"";
            // line 39
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["menu_parent"], "icon_url", [], "any", false, false, true, 39), 39, $this->source), "html", null, true);
            echo "\" style=\"max-width:21px;\">
          <span>";
            // line 40
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["menu_parent"], "title", [], "any", false, false, true, 40), 40, $this->source), "html", null, true);
            echo "</span>
        </a> 

        ";
            // line 43
            if (twig_get_attribute($this->env, $this->source, $context["menu_parent"], "below", [], "any", false, false, true, 43)) {
                // line 44
                echo "          <!-- submenu collapse start (for mobile) -->
          <div class=\"collapse-item collapse\" id=\"";
                // line 45
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["menu_parent"], "mobile_menu_id", [], "any", false, false, true, 45), 45, $this->source), "html", null, true);
                echo "\">
            <ul class=\"btn-toggle-nav list-unstyled small\">
              ";
                // line 47
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["menu_parent"], "below", [], "any", false, false, true, 47));
                foreach ($context['_seq'] as $context["_key"] => $context["menu_child"]) {
                    // line 48
                    echo "                <li>
                  <a href=\"";
                    // line 49
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["menu_child"], "url", [], "any", false, false, true, 49), 49, $this->source), "html", null, true);
                    echo "\" class=\"link-dark ";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((twig_get_attribute($this->env, $this->source, $context["menu_child"], "in_active_trail", [], "any", false, false, true, 49)) ? ("active") : ("")));
                    echo "\">";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["menu_child"], "title", [], "any", false, false, true, 49), 49, $this->source), "html", null, true);
                    echo "</a>
                </li>
              ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['menu_child'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 52
                echo "            </ul>
          </div>
          <!-- submenu collapse end -->
        ";
            }
            // line 56
            echo "      </li>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['menu_parent'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 58
        echo "  </ul>
  <!-- menu end -->
</div>

<!-- submenu start (for desktop) -->
";
        // line 63
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["items"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["menu_parent"]) {
            // line 64
            echo "  ";
            if (twig_get_attribute($this->env, $this->source, $context["menu_parent"], "below", [], "any", false, false, true, 64)) {
                // line 65
                echo "    <div class=\"sub-menu shadow-sm scrollarea\" id=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["menu_parent"], "menu_id", [], "any", false, false, true, 65), 65, $this->source), "html", null, true);
                echo "\" onmouseover=\"showMenu('";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["menu_parent"], "menu_id", [], "any", false, false, true, 65), 65, $this->source), "html", null, true);
                echo "')\"
      onmouseleave=\"hideMenu('";
                // line 66
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["menu_parent"], "menu_id", [], "any", false, false, true, 66), 66, $this->source), "html", null, true);
                echo "')\">
      <div class=\"has-sub-menu\">
        <p class=\"sub-menu-label\">";
                // line 68
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_title_string_filter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["menu_parent"], "title", [], "any", false, false, true, 68), 68, $this->source)), "html", null, true);
                echo "</p>
        <div class=\"list-group\">
          ";
                // line 70
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["menu_parent"], "below", [], "any", false, false, true, 70));
                foreach ($context['_seq'] as $context["_key"] => $context["menu_child"]) {
                    // line 71
                    echo "            <a href=\"";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["menu_child"], "url", [], "any", false, false, true, 71), 71, $this->source), "html", null, true);
                    echo "\" class=\"list-group-item list-group-item-action ";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((twig_get_attribute($this->env, $this->source, $context["menu_child"], "in_active_trail", [], "any", false, false, true, 71)) ? ("active") : ("")));
                    echo "\">";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["menu_child"], "title", [], "any", false, false, true, 71), 71, $this->source), "html", null, true);
                    echo "</a>
          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['menu_child'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 73
                echo "        </div>
      </div>
    </div>
  ";
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['menu_parent'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 78
        echo "<!-- submenu End -->
<!-- sidebar end -->
";
    }

    public function getTemplateName()
    {
        return "themes/custom/telkom_cms/templates/region/sidebar_menu/menu--main.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  201 => 78,  191 => 73,  178 => 71,  174 => 70,  169 => 68,  164 => 66,  157 => 65,  154 => 64,  150 => 63,  143 => 58,  136 => 56,  130 => 52,  117 => 49,  114 => 48,  110 => 47,  105 => 45,  102 => 44,  100 => 43,  94 => 40,  90 => 39,  87 => 38,  82 => 36,  75 => 35,  70 => 33,  61 => 32,  59 => 31,  56 => 30,  52 => 29,  45 => 25,  39 => 21,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/custom/telkom_cms/templates/region/sidebar_menu/menu--main.html.twig", "C:\\laragon\\www\\content-door\\content-management-service\\web\\themes\\custom\\telkom_cms\\templates\\region\\sidebar_menu\\menu--main.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("for" => 29, "if" => 31);
        static $filters = array("escape" => 25, "title" => 68);
        static $functions = array("drupal_region" => 25);

        try {
            $this->sandbox->checkSecurity(
                ['for', 'if'],
                ['escape', 'title'],
                ['drupal_region']
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
