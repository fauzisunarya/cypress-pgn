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

/* themes/custom/telkom_cms/templates/region/header/menu--account.html.twig */
class __TwigTemplate_19235a9d2a26e6db238a9ed82bde2719 extends \Twig\Template
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
        // line 1
        echo "
";
        // line 2
        if (($context["logged_in"] ?? null)) {
            // line 3
            echo "    <li class=\"nav-item dropdown\">
        <a href=\"#\" class=\"nav-link account text-decoration-none\" id=\"dropdownUser2\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">
            
            ";
            // line 6
            if ((twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["user_detail"] ?? null), "profile_picture", [], "any", false, false, true, 6)) > 0)) {
                // line 7
                echo "                ";
                $context["url"] = twig_get_attribute($this->env, $this->source, ($context["user_detail"] ?? null), "profile_picture", [], "any", false, false, true, 7);
                // line 8
                echo "            ";
            } else {
                // line 9
                echo "                ";
                $context["url"] = "https://t3.ftcdn.net/jpg/03/46/83/96/360_F_346839683_6nAPzbhpSkIpb8pmAwufkC7c5eD7wYws.jpg";
                // line 10
                echo "            ";
            }
            // line 11
            echo "
            <img src=\"";
            // line 12
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["url"] ?? null), 12, $this->source), "html", null, true);
            echo "\" alt=\"\" width=\"38\" height=\"38\" class=\"rounded-circle\">
            <h6 class=\"mb-0 ms-3 d-none d-sm-block account-username\">";
            // line 13
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["user_detail"] ?? null), "username", [], "any", false, false, true, 13), 13, $this->source), "html", null, true);
            echo "</h6>
        </a>
        <ul class=\"dropdown-menu text-small shadow\" aria-labelledby=\"dropdownUser2\">
            <li><a class=\"dropdown-item\" href=\"";
            // line 16
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, (($__internal_compile_0 = ($context["items"] ?? null)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0["user.page"] ?? null) : null), "url", [], "any", false, false, true, 16), 16, $this->source), "html", null, true);
            echo "\">Profile</a></li>
            <li><a class=\"dropdown-item\" href=\"";
            // line 17
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["edit_user_url"] ?? null), 17, $this->source), "html", null, true);
            echo "\">Edit Profile</a></li>
            <li><a class=\"dropdown-item\" href=\"";
            // line 18
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, (($__internal_compile_1 = ($context["items"] ?? null)) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1["user.logout"] ?? null) : null), "url", [], "any", false, false, true, 18), 18, $this->source), "html", null, true);
            echo "\">Sign out</a></li>
        </ul>
    </li>
";
        } else {
            // line 22
            echo "    <ul  class=\"ul-parent ul-parent-account\" role=\"menu\">
        <li class=\"li-item li-item-account\" role=\"none\">
            <a href=\"";
            // line 24
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["login_url"] ?? null), 24, $this->source), "html", null, true);
            echo "\" class=\"w3-button li-link li-link-account\" role=\"menuitem\" data-drupal-link-system-path=\"user/login\">Log in</a>
        </li>
    </ul>
";
        }
    }

    public function getTemplateName()
    {
        return "themes/custom/telkom_cms/templates/region/header/menu--account.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  95 => 24,  91 => 22,  84 => 18,  80 => 17,  76 => 16,  70 => 13,  66 => 12,  63 => 11,  60 => 10,  57 => 9,  54 => 8,  51 => 7,  49 => 6,  44 => 3,  42 => 2,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/custom/telkom_cms/templates/region/header/menu--account.html.twig", "C:\\laragon\\www\\content-door\\content-management-service\\web\\themes\\custom\\telkom_cms\\templates\\region\\header\\menu--account.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 2, "set" => 7);
        static $filters = array("length" => 6, "escape" => 12);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['if', 'set'],
                ['length', 'escape'],
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
