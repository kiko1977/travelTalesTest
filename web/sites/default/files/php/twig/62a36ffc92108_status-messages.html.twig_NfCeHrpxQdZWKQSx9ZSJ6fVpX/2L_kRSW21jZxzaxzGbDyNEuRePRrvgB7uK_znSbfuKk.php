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

/* themes/contrib/fortytwo_admin/templates/misc/status-messages.html.twig */
class __TwigTemplate_5ff54be8bce52122cfa6c20fdd49ce3bd2de5450c84cc67f63325a322bc7b8bf extends \Twig\Template
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
        // line 26
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["message_list"] ?? null));
        foreach ($context['_seq'] as $context["type"] => $context["messages"]) {
            // line 27
            echo "  ";
            if (($context["type"] == "status")) {
                // line 28
                echo "    ";
                $context["type"] = "info";
                // line 29
                echo "  ";
            } elseif (($context["type"] == "error")) {
                // line 30
                echo "    ";
                $context["type"] = "danger";
                // line 31
                echo "  ";
            }
            // line 32
            echo "
    ";
            // line 34
            $context["classes"] = [0 => "alert", 1 => ("alert-" . $this->sandbox->ensureToStringAllowed(            // line 36
$context["type"], 36, $this->source)), 2 =>             // line 37
($context["alert_dismissible"] ?? null)];
            // line 40
            echo "
  <div class=\"";
            // line 41
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null)], "method", false, false, true, 41), "class", [], "any", false, false, true, 41), 41, $this->source), "html", null, true);
            echo "\" role=\"alert\" aria-label=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed((($__internal_compile_0 = ($context["status_headings"] ?? null)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0[$context["type"]] ?? null) : null), 41, $this->source), "html", null, true);
            echo "\">
     
    ";
            // line 43
            if ((($context["alert_dismissible"] ?? null) == "alert-dismissible")) {
                // line 44
                echo "      <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
    ";
            }
            // line 46
            echo "     
    ";
            // line 47
            if (($context["type"] == "error")) {
                // line 48
                echo "      <div role=\"alert\">
    ";
            }
            // line 50
            echo "      ";
            if ((($__internal_compile_1 = ($context["status_headings"] ?? null)) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1[$context["type"]] ?? null) : null)) {
                // line 51
                echo "        <h2 class=\"visually-hidden\">";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed((($__internal_compile_2 = ($context["status_headings"] ?? null)) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2[$context["type"]] ?? null) : null), 51, $this->source), "html", null, true);
                echo "</h2>
      ";
            }
            // line 53
            echo "      ";
            if ((twig_length_filter($this->env, $context["messages"]) > 1)) {
                // line 54
                echo "        <ul class=\"messages__list\">
          ";
                // line 55
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($context["messages"]);
                foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
                    // line 56
                    echo "            <li class=\"messages__item\">";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($context["message"], 56, $this->source), "html", null, true);
                    echo "</li>
          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['message'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 58
                echo "        </ul>
      ";
            } else {
                // line 60
                echo "        ";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_first($this->env, $this->sandbox->ensureToStringAllowed($context["messages"], 60, $this->source)), "html", null, true);
                echo "
      ";
            }
            // line 62
            echo "    ";
            if (($context["type"] == "error")) {
                // line 63
                echo "      </div>
    ";
            }
            // line 65
            echo "  </div>
  ";
            // line 67
            echo "  ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "removeClass", [0 => ($context["classes"] ?? null)], "method", false, false, true, 67), 67, $this->source), "html", null, true);
            echo "
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['type'], $context['messages'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
    }

    public function getTemplateName()
    {
        return "themes/contrib/fortytwo_admin/templates/misc/status-messages.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  138 => 67,  135 => 65,  131 => 63,  128 => 62,  122 => 60,  118 => 58,  109 => 56,  105 => 55,  102 => 54,  99 => 53,  93 => 51,  90 => 50,  86 => 48,  84 => 47,  81 => 46,  77 => 44,  75 => 43,  68 => 41,  65 => 40,  63 => 37,  62 => 36,  61 => 34,  58 => 32,  55 => 31,  52 => 30,  49 => 29,  46 => 28,  43 => 27,  39 => 26,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/contrib/fortytwo_admin/templates/misc/status-messages.html.twig", "/var/www/html/web/themes/contrib/fortytwo_admin/templates/misc/status-messages.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("for" => 26, "if" => 27, "set" => 28);
        static $filters = array("escape" => 41, "length" => 53, "first" => 60);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['for', 'if', 'set'],
                ['escape', 'length', 'first'],
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
