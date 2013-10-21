<?php

/* HSPPageBundle:Default:index.html.twig */
class __TwigTemplate_34a7e7ba3c38240add4ce5e2eed2f8741e7a2f2ff25cc97f4c5540300bf4b71b extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("HSPPageBundle::layout.html.twig");

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'content_header' => array($this, 'block_content_header'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "HSPPageBundle::layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_title($context, array $blocks = array())
    {
        echo "Statistics";
    }

    // line 4
    public function block_content_header($context, array $blocks = array())
    {
        echo "Statistics";
    }

    // line 6
    public function block_content($context, array $blocks = array())
    {
        // line 7
        echo "\tstatistics block
";
    }

    public function getTemplateName()
    {
        return "HSPPageBundle:Default:index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  45 => 7,  42 => 6,  36 => 4,  30 => 2,);
    }
}
