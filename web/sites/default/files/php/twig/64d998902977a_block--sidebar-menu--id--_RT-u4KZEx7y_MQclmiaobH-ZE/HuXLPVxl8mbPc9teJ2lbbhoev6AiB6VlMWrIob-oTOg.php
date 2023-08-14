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

/* themes/custom/telkom_cms/templates/region/sidebar_menu/block--sidebar-menu--id--sidebar-menu.html.twig */
class __TwigTemplate_c62d18f3683dae849f52665b45011ff4 extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'content' => [$this, 'block_content'],
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 36
        echo "
";
        // line 38
        $this->displayBlock('content', $context, $blocks);
    }

    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 39
        echo "    ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content"] ?? null), 39, $this->source), "html", null, true);
        echo "
";
    }

    public function getTemplateName()
    {
        return "themes/custom/telkom_cms/templates/region/sidebar_menu/block--sidebar-menu--id--sidebar-menu.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  50 => 39,  43 => 38,  40 => 36,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/custom/telkom_cms/templates/region/sidebar_menu/block--sidebar-menu--id--sidebar-menu.html.twig", "C:\\laragon\\www\\content-door\\content-management-service\\web\\themes\\custom\\telkom_cms\\templates\\region\\sidebar_menu\\block--sidebar-menu--id--sidebar-menu.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("block" => 38);
        static $filters = array("escape" => 39);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['block'],
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
