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

/* themes/contrib/fortytwo_admin/templates/layout/page.html.twig */
class __TwigTemplate_58c3ca3d193e32050e8cb58b6847ee5cbf6e8f5cbb293b388c9b90aa97f76d0e extends \Twig\Template
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
        echo "
<div class=\"outer-wrapper\">
  <header>
    ";
        // line 51
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header", [], "any", false, false, true, 51), 51, $this->source), "html", null, true);
        echo "
  </header>

  ";
        // line 54
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "navigation", [], "any", false, false, true, 54)) {
            // line 55
            echo "    <div class=\"navigation\">
      ";
            // line 56
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "navigation", [], "any", false, false, true, 56), 56, $this->source), "html", null, true);
            echo "
    </div>
  ";
        }
        // line 59
        echo "
  <div class=\"wrapper\">
    ";
        // line 61
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "breadcrumb", [], "any", false, false, true, 61), 61, $this->source), "html", null, true);
        echo "

    <section class=\"content column\">
      ";
        // line 64
        if (($context["messages"] ?? null)) {
            // line 65
            echo "        <div class=\"messages\">
          ";
            // line 66
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["messages"] ?? null), 66, $this->source), "html", null, true);
            echo "
        </div>
      ";
        }
        // line 69
        echo "      ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "help", [], "any", false, false, true, 69), 69, $this->source), "html", null, true);
        echo "

      ";
        // line 71
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "highlighted", [], "any", false, false, true, 71)) {
            // line 72
            echo "        <div class=\"highlighted\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "highlighted", [], "any", false, false, true, 72), 72, $this->source), "html", null, true);
            echo "</div>
      ";
        }
        // line 74
        echo "
      <a id=\"main-content\" tabindex=\"-1\"></a>

      ";
        // line 77
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content", [], "any", false, false, true, 77), 77, $this->source), "html", null, true);
        echo "
    </section>

    ";
        // line 80
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_first", [], "any", false, false, true, 80)) {
            // line 81
            echo "      <aside class=\"column sidebar first\">
        ";
            // line 82
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_first", [], "any", false, false, true, 82), 82, $this->source), "html", null, true);
            echo "
      </aside>  <!-- End first aside. -->
    ";
        }
        // line 85
        echo "
    ";
        // line 86
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_second", [], "any", false, false, true, 86)) {
            // line 87
            echo "      <aside class=\"column sidebar second\">
        ";
            // line 88
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_second", [], "any", false, false, true, 88), 88, $this->source), "html", null, true);
            echo "
      </aside> <!-- End second aside. -->
    ";
        }
        // line 91
        echo "
  </div>

  ";
        // line 94
        if ((twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer", [], "any", false, false, true, 94) || ($context["theme_credit"] ?? null))) {
            // line 95
            echo "    <footer>
      ";
            // line 96
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer", [], "any", false, false, true, 96), 96, $this->source), "html", null, true);
            echo "
      ";
            // line 97
            if (($context["theme_credit"] ?? null)) {
                // line 98
                echo "        <div class = \"theme-credit\">";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["theme_credit"] ?? null), 98, $this->source), "html", null, true);
                echo "</div>
      ";
            }
            // line 100
            echo "    </footer>
  ";
        }
        // line 102
        echo "</div>";
    }

    public function getTemplateName()
    {
        return "themes/contrib/fortytwo_admin/templates/layout/page.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  158 => 102,  154 => 100,  148 => 98,  146 => 97,  142 => 96,  139 => 95,  137 => 94,  132 => 91,  126 => 88,  123 => 87,  121 => 86,  118 => 85,  112 => 82,  109 => 81,  107 => 80,  101 => 77,  96 => 74,  90 => 72,  88 => 71,  82 => 69,  76 => 66,  73 => 65,  71 => 64,  65 => 61,  61 => 59,  55 => 56,  52 => 55,  50 => 54,  44 => 51,  39 => 48,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/contrib/fortytwo_admin/templates/layout/page.html.twig", "/var/www/html/web/themes/contrib/fortytwo_admin/templates/layout/page.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 54);
        static $filters = array("escape" => 51);
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
