<?php

namespace Rvx;

use \Rvx\Twig\Environment;
use \Rvx\Twig\Error\LoaderError;
use \Rvx\Twig\Error\RuntimeError;
use \Rvx\Twig\Extension\CoreExtension;
use \Rvx\Twig\Extension\SandboxExtension;
use \Rvx\Twig\Markup;
use \Rvx\Twig\Sandbox\SecurityError;
use \Rvx\Twig\Sandbox\SecurityNotAllowedTagError;
use \Rvx\Twig\Sandbox\SecurityNotAllowedFilterError;
use \Rvx\Twig\Sandbox\SecurityNotAllowedFunctionError;
use \Rvx\Twig\Source;
use \Rvx\Twig\Template;

/* storeadmin/onboard.twig */
class __TwigTemplate_959ff1cebea74d3afb8a2c11514a6e5b extends Template
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
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        yield "<div class=\"wrap\">
  <div id=\"rvx-admin-onboard\"></div>
</div>
";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "storeadmin/onboard.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo()
    {
        return array ();
    }

    public function getSourceContext()
    {
        return new Source("", "storeadmin/onboard.twig", "/var/www/html/wp-content/plugins/reviewx/resources/views/storeadmin/onboard.twig");
    }
}
