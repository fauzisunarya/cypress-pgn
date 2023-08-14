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

/* themes/custom/telkom_cms/templates/layout/page.html.twig */
class __TwigTemplate_9822f234b6588d4c7b93b035ed5a4f13 extends \Twig\Template
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
        // line 57
        echo "<!-- Sidebar-->
";
        // line 58
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_menu", [], "any", false, false, true, 58), 58, $this->source), "html", null, true);
        echo "

<!-- Page content wrapper-->
<div class=\"content-wrapper\">

    <!-- topbar start -->
      <header class=\"navbar navbar-light navbar-expand-md bg-white sticky-top shadow-sm px-4\">
        <!-- button toggled sidenav start -->
        <button class=\"navbar-toggler border-0\" type=\"button\" id=\"sidebarToggle\">
          <span class=\"navbar-toggler-icon\"></span>
        </button>
        <!-- button toggled sidenav end -->
        <!-- navbar brand start -->
        ";
        // line 71
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "title", [], "any", false, false, true, 71), 71, $this->source), "html", null, true);
        echo "
        <!-- navbar brand start -->
        <ul class=\"navbar-nav ms-auto\">
          ";
        // line 74
        if (($context["logged_in"] ?? null)) {
            // line 75
            echo "            <li class=\"nav-item dropdown dropdown-notification\">
                <a href=\"#\" class=\"nav-link pe-3\" id=\"notification\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">
                <div class=\"nav-notification\">
                    <img src=\"";
            // line 78
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->getUrl("<front>")), "html", null, true);
            echo "themes/custom/telkom_cms/assets/icons/ic-bell-new.svg\" alt=\"\">
                </div>
                <span class=\"badge badge-danger rounded-circle noti-icon-badge\">";
            // line 80
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["unread_notification"] ?? null), 80, $this->source), "html", null, true);
            echo "</span>
                </a>
                <div class=\"dropdown-menu notification-menu dropdown-menu-end animate slideIn\"
                aria-labelledby=\"navbarDropdown\">
                    ";
            // line 84
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["notification"] ?? null));
            $context['loop'] = [
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            ];
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["notif"]) {
                // line 85
                echo "                        <a class=\"dropdown-item\" href=\"";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["notif"], "url", [], "any", false, false, true, 85), 85, $this->source), "html", null, true);
                echo "\">
                            ";
                // line 86
                if (twig_get_attribute($this->env, $this->source, $context["notif"], "is_read", [], "any", false, false, true, 86)) {
                    // line 87
                    echo "                                ";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["notif"], "title", [], "any", false, false, true, 87), 87, $this->source), "html", null, true);
                    echo "
                            ";
                } else {
                    // line 89
                    echo "                                <b>";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["notif"], "title", [], "any", false, false, true, 89), 89, $this->source), "html", null, true);
                    echo "</b>
                            ";
                }
                // line 91
                echo "                        </a>
                        <div class=\"dropdown-divider\"></div>
                        ";
                // line 93
                if (twig_get_attribute($this->env, $this->source, $context["loop"], "last", [], "any", false, false, true, 93)) {
                    // line 94
                    echo "                            <a class=\"dropdown-item text-center text-primary\" href=\"";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["base_url"] ?? null), 94, $this->source), "html", null, true);
                    echo "/notification\">Lihat Semua</a>
                        ";
                }
                // line 96
                echo "                    ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['length'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['notif'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 97
            echo "
                    ";
            // line 98
            if (twig_test_empty(($context["notification"] ?? null))) {
                // line 99
                echo "                        <a class=\"dropdown-item text-center\" href=\"#\">There is no notification</a>
                    ";
            }
            // line 101
            echo "                </div>
            </li>
          ";
        }
        // line 104
        echo "
          ";
        // line 105
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "user_navigation", [], "any", false, false, true, 105), 105, $this->source), "html", null, true);
        echo "          
          
        </ul>
      </header>
      <!-- topbar end -->

      <!-- breadcrumb start -->
      ";
        // line 112
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "breadcrumb", [], "any", false, false, true, 112), 112, $this->source), "html", null, true);
        echo "
      <!-- breadcrumb end -->

      <!-- content start -->
      <div class=\"container-fluid px-4 my-4\">
        ";
        // line 117
        if (($context["is_using_card_class"] ?? null)) {
            // line 118
            echo "            <div class=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((($context["is_front"] ?? null) != true)) ? ("card") : ("")));
            echo "\">
                ";
            // line 119
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content", [], "any", false, false, true, 119), 119, $this->source), "html", null, true);
            echo "
            </div>
        ";
        } else {
            // line 122
            echo "            ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content", [], "any", false, false, true, 122), 122, $this->source), "html", null, true);
            echo "
        ";
        }
        // line 124
        echo "      </div>
      <!-- content end -->

</div>";
    }

    public function getTemplateName()
    {
        return "themes/custom/telkom_cms/templates/layout/page.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  199 => 124,  193 => 122,  187 => 119,  182 => 118,  180 => 117,  172 => 112,  162 => 105,  159 => 104,  154 => 101,  150 => 99,  148 => 98,  145 => 97,  131 => 96,  125 => 94,  123 => 93,  119 => 91,  113 => 89,  107 => 87,  105 => 86,  100 => 85,  83 => 84,  76 => 80,  71 => 78,  66 => 75,  64 => 74,  58 => 71,  42 => 58,  39 => 57,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/custom/telkom_cms/templates/layout/page.html.twig", "C:\\laragon\\www\\content-door\\content-management-service\\web\\themes\\custom\\telkom_cms\\templates\\layout\\page.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 74, "for" => 84);
        static $filters = array("escape" => 58, "render" => 78);
        static $functions = array("url" => 78);

        try {
            $this->sandbox->checkSecurity(
                ['if', 'for'],
                ['escape', 'render'],
                ['url']
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
