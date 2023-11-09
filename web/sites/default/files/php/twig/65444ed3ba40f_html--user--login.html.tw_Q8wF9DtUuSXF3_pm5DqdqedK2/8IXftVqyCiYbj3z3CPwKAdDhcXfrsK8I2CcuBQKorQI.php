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

/* themes/custom/telkom_cms/templates/layout/html--user--login.html.twig */
class __TwigTemplate_50a84307cfbba163bda4c03b19779c50 extends \Twig\Template
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
        echo "<!DOCTYPE html>
<html lang=\"en\">
    <head>
        <meta charset=\"utf-8\" />
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\" />
        <meta name=\"description\" content=\"\" />
        <meta name=\"author\" content=\"\" />
        <title>Telkom Login</title>
        <!-- Favicon-->
        <link rel=\"icon\" type=\"image/x-icon\" href=\"/sites/default/files/favicon.ico\" />
        <title>";
        // line 11
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->safeJoin($this->env, $this->sandbox->ensureToStringAllowed(($context["head_title"] ?? null), 11, $this->source), " | "));
        echo "</title>
        <css-placeholder token=\"";
        // line 12
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["placeholder_token"] ?? null), 12, $this->source), "html", null, true);
        echo "\">
        <js-placeholder token=\"";
        // line 13
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["placeholder_token"] ?? null), 13, $this->source), "html", null, true);
        echo "\">
    </head>
    <body>
        ";
        // line 16
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("telkom_cms/global-styling"), "html", null, true);
        echo "
        ";
        // line 17
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("telkom_cms/global-scripts-head"), "html", null, true);
        echo "

        <div id=\"login-wrapper\">
            <div class=\"col-lg-4 col-md-6\">
                <div class=\"card login-form\">
                    <div class=\"card-body\">
                        <div class=\"card-title\">
                            <img class=\"login-logo\" alt=\"telkom-logo\">
                            <h6>Selamat Datang</h6>
                            <div class=\"gap-2 d-flex justify-content-center forgot-pass\">
                                <p><a href=\"";
        // line 27
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["forgot_password_url"] ?? null), 27, $this->source), "html", null, true);
        echo "\">Lupa Password</a></p>
                            </div>
                        </div>
                        <div class=\"card-text\">
                          ";
        // line 31
        if (($context["disabled_login_otp"] ?? null)) {
            // line 32
            echo "                            ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content", [], "any", false, false, true, 32), 32, $this->source), "html", null, true);
            echo "
                          ";
        } else {
            // line 34
            echo "                            <form class=\"row form-login\" enctype=\"multipart/form-data\" method=\"post\">
                                <div class=\"form-group col-md-12 form-email\">
                                    <label for=\"email\">Email / Username / NIK</label>
                                    <input type=\"text\" name=\"username\" class=\"login-form-control\">
                                </div>
                                <div class=\"form-group col-md-12 form-password\">
                                    <label for=\"password\">Password</label>
                                    <input type=\"password\" name=\"password\" class=\"login-form-control\">
                                </div>
                                <div class=\"login-cta\">
                                    <div class=\"g-recaptcha brochure__form__captcha d-inline-block\" data-sitekey=\"";
            // line 44
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["recaptcha_sitekey"] ?? null), 44, $this->source), "html", null, true);
            echo "\"></div>
                                    <button type=\"button\" class=\"btn btn-primary btn-login\">Login</button>
                                </div>
                            </form>
                            <form class=\"form-otp d-none justify-content-center align-items-center\" enctype=\"multipart/form-data\" method=\"post\">
                                <input type=\"hidden\" name=\"user_id\" value=\"\">
                                <div class=\"position-relative\">
                                    <div class=\"card p-2 text-center\">
                                        <h6 class=\"otp-header\">
                                            Please enter the correct OTP code <br> to verify your access
                                        </h6>
                                        <div class=\"otp-subheader\">
                                            <span>A code has been sent to your telegram account</span>
                                        </div>
                                        <div id=\"otp\" class=\"otp-inputs d-flex flex-row justify-content-center mt-2\"> 
                                            <input class=\"m-2 text-center form-control rounded\" name=\"otp_code[]\" type=\"text\" id=\"first\" maxlength=\"1\" />
                                            <input class=\"m-2 text-center form-control rounded\" name=\"otp_code[]\" type=\"text\" id=\"second\" maxlength=\"1\" />
                                            <input class=\"m-2 text-center form-control rounded\" name=\"otp_code[]\" type=\"text\" id=\"third\" maxlength=\"1\" />
                                            <input class=\"m-2 text-center form-control rounded\" name=\"otp_code[]\" type=\"text\" id=\"fourth\" maxlength=\"1\" />
                                            <input class=\"m-2 text-center form-control rounded\" name=\"otp_code[]\" type=\"text\" id=\"fifth\" maxlength=\"1\" />
                                            <input class=\"m-2 text-center form-control rounded\" name=\"otp_code[]\" type=\"text\" id=\"sixth\" maxlength=\"1\" />
                                        </div>
                                        <div class=\"mt-4\">
                                            <button class=\"btn btn-danger px-4 btn-validate d-inline\">Validate</button>
                                            <span class=\"my-2 fw-bold fs-6 d-none align-content-center justify-content-center spn-separator\">Or</span>
                                            <button class=\"btn btn-secondary px-4 btn-resend d-none\">Resend</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                          ";
        }
        // line 75
        echo "                        </div>
                    </div>
                </div>
            </div>
        </div>
        <js-bottom-placeholder token=\"";
        // line 80
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["placeholder_token"] ?? null), 80, $this->source), "html", null, true);
        echo "\">
        ";
        // line 81
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("telkom_cms/global-scripts"), "html", null, true);
        echo "
    </body>
</html>
";
    }

    public function getTemplateName()
    {
        return "themes/custom/telkom_cms/templates/layout/html--user--login.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  154 => 81,  150 => 80,  143 => 75,  109 => 44,  97 => 34,  91 => 32,  89 => 31,  82 => 27,  69 => 17,  65 => 16,  59 => 13,  55 => 12,  51 => 11,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/custom/telkom_cms/templates/layout/html--user--login.html.twig", "C:\\laragon\\www\\cms\\web\\themes\\custom\\telkom_cms\\templates\\layout\\html--user--login.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 31);
        static $filters = array("safe_join" => 11, "escape" => 12);
        static $functions = array("attach_library" => 16);

        try {
            $this->sandbox->checkSecurity(
                ['if'],
                ['safe_join', 'escape'],
                ['attach_library']
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
