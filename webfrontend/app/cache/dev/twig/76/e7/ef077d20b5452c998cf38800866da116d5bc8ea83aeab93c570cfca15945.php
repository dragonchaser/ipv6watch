<?php

/* HSPAdminBundle:Default:index.html.twig */
class __TwigTemplate_76e7ef077d20b5452c998cf38800866da116d5bc8ea83aeab93c570cfca15945 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("HSPPageBundle::layout.html.twig");

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'content_header' => array($this, 'block_content_header'),
            'header_menu' => array($this, 'block_header_menu'),
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

    // line 3
    public function block_title($context, array $blocks = array())
    {
        echo "Administration";
    }

    // line 5
    public function block_content_header($context, array $blocks = array())
    {
        echo "Administration";
    }

    // line 6
    public function block_header_menu($context, array $blocks = array())
    {
        // line 7
        echo $this->env->getExtension('knp_menu')->render("HSPAdminBundle:Builder:mainMenu");
        echo "
";
    }

    // line 9
    public function block_content($context, array $blocks = array())
    {
        // line 10
        echo "\tadmin area
";
    }

    public function getTemplateName()
    {
        return "HSPAdminBundle:Default:index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  55 => 10,  52 => 9,  46 => 7,  43 => 6,  37 => 5,  31 => 3,);
    }
}
