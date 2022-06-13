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

/* themes/contrib/uikit/templates/layout/page.html.twig */
class __TwigTemplate_1f3217e5b12d061a2cf7f033386b845576180ed5f15fda926d076c9938d42ff8 extends \Twig\Template
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
        // line 49
        echo "<div id=\"page-wrapper\"";
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "offcanvas", [], "any", false, false, true, 49)) {
            echo " class=\"uk-offcanvas-content\"";
        }
        echo ">
  <header";
        // line 50
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["header_attributes"] ?? null), 50, $this->source), "html", null, true);
        echo ">
    ";
        // line 51
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "navbar", [], "any", false, false, true, 51)) {
            // line 52
            echo "      <nav";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["navbar_attributes"] ?? null), 52, $this->source), "html", null, true);
            echo ">
        ";
            // line 53
            if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "offcanvas", [], "any", false, false, true, 53)) {
                // line 54
                echo "          <a href=\"#offcanvas\" class=\"uk-navbar-toggle uk-hidden@m\" uk-toggle uk-navbar-toggle-icon></a>
        ";
            }
            // line 56
            echo "
        ";
            // line 57
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "navbar", [], "any", false, false, true, 57), 57, $this->source), "html", null, true);
            echo "
      </nav>
    ";
        }
        // line 60
        echo "  </header>

  <div";
        // line 62
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["page_attributes"] ?? null), 62, $this->source), "html", null, true);
        echo ">
    <div id=\"page--wrapper\" class=\"uk-grid\" uk-grid>
      <main";
        // line 64
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content_attributes"] ?? null), 64, $this->source), "html", null, true);
        echo ">
        <a id=\"main-content\" tabindex=\"-1\"></a>
        ";
        // line 66
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content", [], "any", false, false, true, 66), 66, $this->source), "html", null, true);
        echo "
      </main>

      ";
        // line 69
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_first", [], "any", false, false, true, 69)) {
            // line 70
            echo "        <aside";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["sidebar_first_attributes"] ?? null), 70, $this->source), "html", null, true);
            echo ">
          ";
            // line 71
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_first", [], "any", false, false, true, 71), 71, $this->source), "html", null, true);
            echo "
        </aside>
      ";
        }
        // line 74
        echo "
      ";
        // line 75
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_second", [], "any", false, false, true, 75)) {
            // line 76
            echo "        <aside";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["sidebar_second_attributes"] ?? null), 76, $this->source), "html", null, true);
            echo ">
          ";
            // line 77
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "sidebar_second", [], "any", false, false, true, 77), 77, $this->source), "html", null, true);
            echo "
        </aside>
      ";
        }
        // line 80
        echo "    </div>
  </div>

  ";
        // line 83
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer", [], "any", false, false, true, 83)) {
            // line 84
            echo "    <footer role=\"contentinfo\">
      <div class=\"uk-container uk-margin-top\">
        ";
            // line 86
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer", [], "any", false, false, true, 86), 86, $this->source), "html", null, true);
            echo "
      </div>
    </footer>
  ";
        }
        // line 90
        echo "
  ";
        // line 91
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "offcanvas", [], "any", false, false, true, 91)) {
            // line 92
            echo "    <div id=\"offcanvas\" uk-offcanvas>
      ";
            // line 93
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "offcanvas", [], "any", false, false, true, 93), 93, $this->source), "html", null, true);
            echo "
    </div>
  ";
        }
        // line 96
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "themes/contrib/uikit/templates/layout/page.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  153 => 96,  147 => 93,  144 => 92,  142 => 91,  139 => 90,  132 => 86,  128 => 84,  126 => 83,  121 => 80,  115 => 77,  110 => 76,  108 => 75,  105 => 74,  99 => 71,  94 => 70,  92 => 69,  86 => 66,  81 => 64,  76 => 62,  72 => 60,  66 => 57,  63 => 56,  59 => 54,  57 => 53,  52 => 52,  50 => 51,  46 => 50,  39 => 49,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/contrib/uikit/templates/layout/page.html.twig", "/var/www/html/web/themes/contrib/uikit/templates/layout/page.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 49);
        static $filters = array("escape" => 50);
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
