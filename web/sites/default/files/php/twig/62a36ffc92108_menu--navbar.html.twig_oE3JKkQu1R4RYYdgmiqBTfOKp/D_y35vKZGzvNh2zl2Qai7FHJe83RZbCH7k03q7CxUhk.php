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

/* themes/contrib/uikit/templates/navigation/menu--navbar.html.twig */
class __TwigTemplate_45ad0b31a732f0551def4e15c468a05d3a10515a168113f63857d7dfc956c82c extends \Twig\Template
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
        // line 25
        $macros["menus"] = $this->macros["menus"] = $this;
        // line 26
        echo "
";
        // line 31
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(twig_call_macro($macros["menus"], "macro_menu_links", [($context["items"] ?? null), ($context["attributes"] ?? null), 0], 31, $context, $this->getSourceContext()));
        echo "

";
    }

    // line 33
    public function macro_menu_links($__items__ = null, $__attributes__ = null, $__menu_level__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "items" => $__items__,
            "attributes" => $__attributes__,
            "menu_level" => $__menu_level__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start(function () { return ''; });
        try {
            // line 34
            echo "  ";
            $macros["menus"] = $this;
            // line 35
            echo "  ";
            if (($context["items"] ?? null)) {
                // line 36
                echo "    ";
                // line 37
                $context["classes"] = [0 => (((                // line 38
($context["menu_level"] ?? null) == 0)) ? ("uk-navbar-nav") : ("")), 1 => (((                // line 39
($context["menu_level"] ?? null) > 0)) ? ("uk-nav") : ("")), 2 => (((                // line 40
($context["menu_level"] ?? null) > 0)) ? ("uk-navbar-dropdown-nav") : (""))];
                // line 43
                echo "
    ";
                // line 44
                if ((($context["menu_level"] ?? null) > 0)) {
                    // line 45
                    echo "      <div class=\"uk-navbar-dropdown\">
    ";
                }
                // line 47
                echo "
    <ul";
                // line 48
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null)], "method", false, false, true, 48), 48, $this->source), "html", null, true);
                echo ">
    ";
                // line 49
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(($context["items"] ?? null));
                foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                    // line 50
                    echo "      <li";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "attributes", [], "any", false, false, true, 50), 50, $this->source), "html", null, true);
                    echo ">
        ";
                    // line 51
                    if (twig_get_attribute($this->env, $this->source, $context["item"], "is_header", [], "any", false, false, true, 51)) {
                        // line 52
                        echo "          ";
                        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "title", [], "any", false, false, true, 52), 52, $this->source), "html", null, true);
                        echo "
        ";
                    } elseif ( !twig_get_attribute($this->env, $this->source,                     // line 53
$context["item"], "is_divider", [], "any", false, false, true, 53)) {
                        // line 54
                        echo "          ";
                        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->getLink($this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "title", [], "any", false, false, true, 54), 54, $this->source), $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "url", [], "any", false, false, true, 54), 54, $this->source)), "html", null, true);
                        echo "
        ";
                    }
                    // line 56
                    echo "        ";
                    if (twig_get_attribute($this->env, $this->source, $context["item"], "below", [], "any", false, false, true, 56)) {
                        // line 57
                        echo "          ";
                        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(twig_call_macro($macros["menus"], "macro_menu_links", [twig_get_attribute($this->env, $this->source, $context["item"], "below", [], "any", false, false, true, 57), twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "removeClass", [0 => ($context["classes"] ?? null)], "method", false, false, true, 57), (($context["menu_level"] ?? null) + 1)], 57, $context, $this->getSourceContext()));
                        echo "
        ";
                    }
                    // line 59
                    echo "      </li>
    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 61
                echo "    </ul>

    ";
                // line 63
                if ((($context["menu_level"] ?? null) > 0)) {
                    // line 64
                    echo "      </div>
    ";
                }
                // line 66
                echo "  ";
            }

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    public function getTemplateName()
    {
        return "themes/contrib/uikit/templates/navigation/menu--navbar.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  145 => 66,  141 => 64,  139 => 63,  135 => 61,  128 => 59,  122 => 57,  119 => 56,  113 => 54,  111 => 53,  106 => 52,  104 => 51,  99 => 50,  95 => 49,  91 => 48,  88 => 47,  84 => 45,  82 => 44,  79 => 43,  77 => 40,  76 => 39,  75 => 38,  74 => 37,  72 => 36,  69 => 35,  66 => 34,  51 => 33,  44 => 31,  41 => 26,  39 => 25,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/contrib/uikit/templates/navigation/menu--navbar.html.twig", "/var/www/html/web/themes/contrib/uikit/templates/navigation/menu--navbar.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("import" => 25, "macro" => 33, "if" => 35, "set" => 37, "for" => 49);
        static $filters = array("escape" => 48);
        static $functions = array("link" => 54);

        try {
            $this->sandbox->checkSecurity(
                ['import', 'macro', 'if', 'set', 'for'],
                ['escape'],
                ['link']
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
