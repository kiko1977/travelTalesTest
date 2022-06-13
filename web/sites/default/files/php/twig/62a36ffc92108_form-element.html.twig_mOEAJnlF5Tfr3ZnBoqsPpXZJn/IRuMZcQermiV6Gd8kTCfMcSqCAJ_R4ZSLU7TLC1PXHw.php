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

/* themes/contrib/uikit/templates/form/form-element.html.twig */
class __TwigTemplate_0f8e318f6c717895d04eced4ea1a4176e948aa0c6b27917d4f05b37a40f040b9 extends \Twig\Template
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
        // line 51
        $context["classes"] = [0 => "js-form-item", 1 => "form-item", 2 => ("js-form-type-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(        // line 54
($context["type"] ?? null), 54, $this->source))), 3 => ("form-type-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(        // line 55
($context["type"] ?? null), 55, $this->source))), 4 => ("js-form-item-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(        // line 56
($context["name"] ?? null), 56, $this->source))), 5 => ("form-item-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(        // line 57
($context["name"] ?? null), 57, $this->source))), 6 => "uk-margin"];
        // line 62
        $context["description_classes"] = [0 => (((        // line 63
($context["description_display"] ?? null) == "invisible")) ? ("visually-hidden") : ("")), 1 => "uk-text-meta", 2 => "uk-margin-small-top"];
        // line 68
        echo "<div";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null)], "method", false, false, true, 68), 68, $this->source), "html", null, true);
        echo ">
  ";
        // line 69
        if (twig_in_filter(($context["label_display"] ?? null), [0 => "before", 1 => "invisible"])) {
            // line 70
            echo "    ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["label"] ?? null), 70, $this->source), "html", null, true);
            echo "
  ";
        }
        // line 72
        echo "
  ";
        // line 73
        if (((($context["description_display"] ?? null) == "before") && twig_get_attribute($this->env, $this->source, ($context["description"] ?? null), "content", [], "any", false, false, true, 73))) {
            // line 74
            echo "    <p";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["description"] ?? null), "attributes", [], "any", false, false, true, 74), "addClass", [0 => ($context["description_classes"] ?? null)], "method", false, false, true, 74), 74, $this->source), "html", null, true);
            echo ">
      ";
            // line 75
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["description"] ?? null), "content", [], "any", false, false, true, 75), 75, $this->source), "html", null, true);
            echo "
    </p>
  ";
        }
        // line 78
        echo "
  <div class=\"uk-form-controls\">
    ";
        // line 80
        if ( !twig_test_empty(($context["prefix"] ?? null))) {
            // line 81
            echo "      <span class=\"field-prefix\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["prefix"] ?? null), 81, $this->source), "html", null, true);
            echo "</span>
    ";
        }
        // line 83
        echo "
    ";
        // line 84
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["children"] ?? null), 84, $this->source), "html", null, true);
        echo "

    ";
        // line 86
        if ( !twig_test_empty(($context["suffix"] ?? null))) {
            // line 87
            echo "      <span class=\"field-suffix\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["suffix"] ?? null), 87, $this->source), "html", null, true);
            echo "</span>
    ";
        }
        // line 89
        echo "  </div>

  ";
        // line 91
        if ((($context["label_display"] ?? null) == "after")) {
            // line 92
            echo "    ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["label"] ?? null), 92, $this->source), "html", null, true);
            echo "
  ";
        }
        // line 94
        echo "
  ";
        // line 95
        if (($context["errors"] ?? null)) {
            // line 96
            echo "    <div class=\"uk-alert uk-alert-danger\">
      ";
            // line 97
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["errors"] ?? null), 97, $this->source), "html", null, true);
            echo "
    </div>
  ";
        }
        // line 100
        echo "
  ";
        // line 101
        if ((twig_in_filter(($context["description_display"] ?? null), [0 => "after", 1 => "invisible"]) && twig_get_attribute($this->env, $this->source, ($context["description"] ?? null), "content", [], "any", false, false, true, 101))) {
            // line 102
            echo "    <p";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["description"] ?? null), "attributes", [], "any", false, false, true, 102), "addClass", [0 => ($context["description_classes"] ?? null)], "method", false, false, true, 102), 102, $this->source), "html", null, true);
            echo ">
      ";
            // line 103
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["description"] ?? null), "content", [], "any", false, false, true, 103), 103, $this->source), "html", null, true);
            echo "
    </p>
  ";
        }
        // line 106
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "themes/contrib/uikit/templates/form/form-element.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  147 => 106,  141 => 103,  136 => 102,  134 => 101,  131 => 100,  125 => 97,  122 => 96,  120 => 95,  117 => 94,  111 => 92,  109 => 91,  105 => 89,  99 => 87,  97 => 86,  92 => 84,  89 => 83,  83 => 81,  81 => 80,  77 => 78,  71 => 75,  66 => 74,  64 => 73,  61 => 72,  55 => 70,  53 => 69,  48 => 68,  46 => 63,  45 => 62,  43 => 57,  42 => 56,  41 => 55,  40 => 54,  39 => 51,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/contrib/uikit/templates/form/form-element.html.twig", "/var/www/html/web/themes/contrib/uikit/templates/form/form-element.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 51, "if" => 69);
        static $filters = array("clean_class" => 54, "escape" => 68);
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
