<?php

/* HSPPageBundle::layout.html.twig */
class __TwigTemplate_a06e033572bf5a80866cb963ab31d4b791073e8d5f6fb06b7aac0bdb6af02ed9 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("TwigBundle::layout.html.twig");

        $this->blocks = array(
            'head' => array($this, 'block_head'),
            'title' => array($this, 'block_title'),
            'body' => array($this, 'block_body'),
            'header_menu' => array($this, 'block_header_menu'),
            'content_header' => array($this, 'block_content_header'),
            'content_header_more' => array($this, 'block_content_header_more'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "TwigBundle::layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_head($context, array $blocks = array())
    {
        // line 4
        echo "    <link rel=\"icon\" sizes=\"16x16\" href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("favicon.ico"), "html", null, true);
        echo "\" />
    <link rel=\"stylesheet\" href=\"";
        // line 5
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/acmedemo/css/demo.css"), "html", null, true);
        echo "\" />
";
    }

    // line 8
    public function block_title($context, array $blocks = array())
    {
        echo "Administration";
    }

    // line 9
    public function block_body($context, array $blocks = array())
    {
        // line 10
        $this->displayBlock('header_menu', $context, $blocks);
        // line 12
        echo "    ";
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "session"), "flashbag"), "get", array(0 => "notice"), "method"));
        foreach ($context['_seq'] as $context["_key"] => $context["flashMessage"]) {
            // line 13
            echo "        <div class=\"flash-message\">
            <em>Notice</em>: ";
            // line 14
            echo twig_escape_filter($this->env, (isset($context["flashMessage"]) ? $context["flashMessage"] : $this->getContext($context, "flashMessage")), "html", null, true);
            echo "
        </div>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['flashMessage'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 17
        echo "
    ";
        // line 18
        $this->displayBlock('content_header', $context, $blocks);
        // line 27
        echo "
    <div class=\"block\">
        ";
        // line 29
        $this->displayBlock('content', $context, $blocks);
        // line 30
        echo "    </div>

    ";
        // line 32
        if (array_key_exists("code", $context)) {
            // line 33
            echo "        <h2>Code behind this page</h2>
        <div class=\"block\">
            <div class=\"symfony-content\">";
            // line 35
            echo (isset($context["code"]) ? $context["code"] : $this->getContext($context, "code"));
            echo "</div>
        </div>
    ";
        }
    }

    // line 10
    public function block_header_menu($context, array $blocks = array())
    {
    }

    // line 18
    public function block_content_header($context, array $blocks = array())
    {
        // line 19
        echo "        <ul id=\"menu\">
            ";
        // line 20
        $this->displayBlock('content_header_more', $context, $blocks);
        // line 23
        echo "        </ul>

        <div style=\"clear: both\"></div>
    ";
    }

    // line 20
    public function block_content_header_more($context, array $blocks = array())
    {
        // line 21
        echo "                <li><a href=\"";
        echo $this->env->getExtension('routing')->getPath("_demo");
        echo "\">Demo Home</a></li>
            ";
    }

    // line 29
    public function block_content($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "HSPPageBundle::layout.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  135 => 29,  128 => 21,  125 => 20,  118 => 23,  116 => 20,  113 => 19,  110 => 18,  105 => 10,  97 => 35,  93 => 33,  91 => 32,  87 => 30,  85 => 29,  81 => 27,  79 => 18,  76 => 17,  67 => 14,  64 => 13,  59 => 12,  57 => 10,  54 => 9,  48 => 8,  42 => 5,  34 => 3,  55 => 10,  52 => 9,  46 => 7,  43 => 6,  37 => 4,  31 => 3,);
    }
}
