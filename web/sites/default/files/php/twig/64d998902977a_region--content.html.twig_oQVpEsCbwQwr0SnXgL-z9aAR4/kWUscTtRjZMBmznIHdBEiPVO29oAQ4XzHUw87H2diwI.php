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

/* themes/custom/telkom_cms/templates/layout/region--content.html.twig */
class __TwigTemplate_6624ce6c9be830ed1a34586423dbae71 extends \Twig\Template
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
        if (($context["is_people_page"] ?? null)) {
            // line 2
            echo "<a
    href=\"";
            // line 3
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["create_user_url"] ?? null), 3, $this->source), "html", null, true);
            echo "\"
    class=\"button button--primary js-form-submit form-submit\"
    id=\"add-user-button\"
    style=\"color: #fff; border-color: #1e5c90; background-color: #0071b8; background-image: -webkit-linear-gradient(top, #007bc6, #0071b8); background-image: linear-gradient(to bottom, #007bc6, #0071b8); text-shadow: 0 1px hsla(0, 0%, 0%, 0.5); font-weight: 700; padding: 10px; display: inline-block; border-radius: 30px; margin-bottom: 20px;\"
>Add User</a>
";
        }
        // line 9
        if (($context["content"] ?? null)) {
            // line 10
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content"] ?? null), 10, $this->source), "html", null, true);
            echo "
";
        }
    }

    public function getTemplateName()
    {
        return "themes/custom/telkom_cms/templates/layout/region--content.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  55 => 10,  53 => 9,  44 => 3,  41 => 2,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/custom/telkom_cms/templates/layout/region--content.html.twig", "C:\\laragon\\www\\content-door\\content-management-service\\web\\themes\\custom\\telkom_cms\\templates\\layout\\region--content.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 1);
        static $filters = array("escape" => 3);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['if'],
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
