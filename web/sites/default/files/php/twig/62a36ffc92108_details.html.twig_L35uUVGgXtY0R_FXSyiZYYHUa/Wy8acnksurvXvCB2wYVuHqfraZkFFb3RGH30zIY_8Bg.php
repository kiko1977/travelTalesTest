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

/* themes/contrib/vartheme_admin/templates/form/details.html.twig */
class __TwigTemplate_e2e2ec9a29e3b465d4917fe152aad656eb5f407069904b05ea9fa52c41771ff2 extends \Twig\Template
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
        // line 17
        echo "<details";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["attributes"] ?? null), 17, $this->source), "html", null, true);
        echo ">";
        // line 18
        if (($context["title"] ?? null)) {
            // line 20
            $context["summary_classes"] = [0 => ((            // line 21
($context["required"] ?? null)) ? ("js-form-required") : ("")), 1 => ((            // line 22
($context["required"] ?? null)) ? ("form-required") : (""))];
            // line 25
            echo "
    <summary";
            // line 26
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["summary_attributes"] ?? null), "addClass", [0 => ($context["summary_classes"] ?? null)], "method", false, false, true, 26), 26, $this->source), "html", null, true);
            echo ">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->sandbox->ensureToStringAllowed(($context["title"] ?? null), 26, $this->source));
            echo "</summary>";
        }
        // line 28
        echo "<div class=\"details-wrapper\">
    ";
        // line 29
        if (($context["errors"] ?? null)) {
            // line 30
            echo "      <div class=\"form-item--error-message\">
        <strong>";
            // line 31
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["errors"] ?? null), 31, $this->source), "html", null, true);
            echo "</strong>
      </div>
    ";
        }
        // line 34
        if (($context["description"] ?? null)) {
            // line 35
            echo "<div class=\"details-description\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["description"] ?? null), 35, $this->source), "html", null, true);
            echo "</div>";
        }
        // line 37
        if (($context["children"] ?? null)) {
            // line 38
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["children"] ?? null), 38, $this->source), "html", null, true);
        }
        // line 40
        if (($context["value"] ?? null)) {
            // line 41
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["value"] ?? null), 41, $this->source), "html", null, true);
        }
        // line 43
        echo "</div>
</details>
";
    }

    public function getTemplateName()
    {
        return "themes/contrib/vartheme_admin/templates/form/details.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  89 => 43,  86 => 41,  84 => 40,  81 => 38,  79 => 37,  74 => 35,  72 => 34,  66 => 31,  63 => 30,  61 => 29,  58 => 28,  52 => 26,  49 => 25,  47 => 22,  46 => 21,  45 => 20,  43 => 18,  39 => 17,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/contrib/vartheme_admin/templates/form/details.html.twig", "/var/www/html/web/themes/contrib/vartheme_admin/templates/form/details.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 18, "set" => 20);
        static $filters = array("escape" => 17, "raw" => 26);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['if', 'set'],
                ['escape', 'raw'],
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
