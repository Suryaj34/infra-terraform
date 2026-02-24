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

/* storeadmin/dashboard.twig */
class __TwigTemplate_9b84a56cc1d632e9a1c1e1f8ea046c19 extends Template
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
  <div id=\"rvx-admin-dashboard\"></div>
</div>";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "storeadmin/dashboard.twig";
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
        return new Source("", "storeadmin/dashboard.twig", "/var/www/html/wp-content/plugins/reviewx/resources/views/storeadmin/dashboard.twig");
    }
}
