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

/* themes/contrib/uikit/templates/navigation/menu--offcanvas.html.twig */
class __TwigTemplate_601eb7a9ec70063a6d20a823d6a40c21587279b45a7e812db4316783d7c7f81b extends \Twig\Template
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
        $macros["menus"] = $this->macros["menus"] = $this;
        // line 27
        echo "
";
        // line 32
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(twig_call_macro($macros["menus"], "macro_menu_links", [($context["items"] ?? null), ($context["attributes"] ?? null), 0], 32, $context, $this->getSourceContext()));
        echo "

";
    }

    // line 34
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
            // line 35
            echo "  ";
            $macros["menus"] = $this;
            // line 36
            echo "  ";
            if (($context["items"] ?? null)) {
                // line 37
                echo "    ";
                // line 38
                $context["classes"] = [0 => (((                // line 39
($context["menu_level"] ?? null) == 0)) ? ("uk-nav") : ("")), 1 => (((                // line 40
($context["menu_level"] ?? null) == 0)) ? ("uk-nav-default") : ("")), 2 => (((                // line 41
($context["menu_level"] ?? null) == 0)) ? ("uk-nav-parent-icon") : ("")), 3 => (((                // line 42
($context["menu_level"] ?? null) > 0)) ? ("uk-nav-sub") : (""))];
                // line 45
                echo "
    <ul";
                // line 46
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null)], "method", false, false, true, 46), 46, $this->source), "html", null, true);
                if ((($context["menu_level"] ?? null) == 0)) {
                    echo " uk-nav";
                }
                echo ">
    ";
                // line 47
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(($context["items"] ?? null));
                foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                    // line 48
                    echo "      ";
                    $context["item_classes"] = [0 => ((twig_get_attribute($this->env, $this->source, $context["item"], "below", [], "any", false, false, true, 48)) ? ("uk-parent") : (""))];
                    // line 49
                    echo "      ";
                    if (twig_get_attribute($this->env, $this->source, $context["item"], "display_item", [], "any", false, false, true, 49)) {
                        // line 50
                        echo "        <li";
                        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["item"], "attributes", [], "any", false, false, true, 50), "addClass", [0 => ($context["item_classes"] ?? null)], "method", false, false, true, 50), 50, $this->source), "html", null, true);
                        echo ">
          ";
                        // line 51
                        if ((twig_get_attribute($this->env, $this->source, $context["item"], "has_url", [], "any", false, false, true, 51) || twig_get_attribute($this->env, $this->source, $context["item"], "is_external", [], "any", false, false, true, 51))) {
                            // line 52
                            echo "            ";
                            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->getLink($this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "title", [], "any", false, false, true, 52), 52, $this->source), $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "url", [], "any", false, false, true, 52), 52, $this->source)), "html", null, true);
                            echo "
          ";
                        } else {
                            // line 54
                            echo "            <a href=\"#\">";
                            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "title", [], "any", false, false, true, 54), 54, $this->source), "html", null, true);
                            echo "</a>
          ";
                        }
                        // line 56
                        echo "          ";
                        if (twig_get_attribute($this->env, $this->source, $context["item"], "below", [], "any", false, false, true, 56)) {
                            // line 57
                            echo "            ";
                            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(twig_call_macro($macros["menus"], "macro_menu_links", [twig_get_attribute($this->env, $this->source, $context["item"], "below", [], "any", false, false, true, 57), twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "removeClass", [0 => ($context["classes"] ?? null)], "method", false, false, true, 57), (($context["menu_level"] ?? null) + 1)], 57, $context, $this->getSourceContext()));
                            echo "
          ";
                        }
                        // line 59
                        echo "        ";
                    }
                    // line 60
                    echo "      </li>
    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 62
                echo "    </ul>

  ";
            }

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    public function getTemplateName()
    {
        return "themes/contrib/uikit/templates/navigation/menu--offcanvas.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  138 => 62,  131 => 60,  128 => 59,  122 => 57,  119 => 56,  113 => 54,  107 => 52,  105 => 51,  100 => 50,  97 => 49,  94 => 48,  90 => 47,  83 => 46,  80 => 45,  78 => 42,  77 => 41,  76 => 40,  75 => 39,  74 => 38,  72 => 37,  69 => 36,  66 => 35,  51 => 34,  44 => 32,  41 => 27,  39 => 26,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/contrib/uikit/templates/navigation/menu--offcanvas.html.twig", "/var/www/html/web/themes/contrib/uikit/templates/navigation/menu--offcanvas.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("import" => 26, "macro" => 34, "if" => 36, "set" => 38, "for" => 47);
        static $filters = array("escape" => 46);
        static $functions = array("link" => 52);

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
