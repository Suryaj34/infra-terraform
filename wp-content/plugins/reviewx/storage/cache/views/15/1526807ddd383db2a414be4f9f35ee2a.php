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

/* storefront/my-account/review-form.twig */
class __TwigTemplate_e4a120b343c7cf96bbebd8ee34ad7d52 extends Template
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
        // line 1
        yield "<div x-data=\"__reviewXState__()\"
     x-init=\"await initializeMyAccountReviewForm({data: '";
        // line 2
        yield $this->env->getRuntime('Rvx\Twig\Runtime\EscaperRuntime')->escape(($context["data"] ?? null), "html", null, true);
        yield "', formLevelData: '";
        yield $this->env->getRuntime('Rvx\Twig\Runtime\EscaperRuntime')->escape(($context["formLevelData"] ?? null), "html", null, true);
        yield "'})\"
    @notify-close-success-modal=\"()=>{
        showReviewSuccessModal = false;
        document.getElementById('reviewxForm')?.classList.add('hidden');
        document.getElementById('isShowTable')?.classList.remove('hidden');
    }\">

    ";
        // line 9
        yield from         $this->loadTemplate("storefront/widget/Features/ReviewForm/index.twig", "storefront/my-account/review-form.twig", 9)->unwrap()->yield($context);
        // line 10
        yield "    ";
        yield from         $this->loadTemplate("storefront/widget/Features/Reviews/Modals/ReviewSuccessModal.twig", "storefront/my-account/review-form.twig", 10)->unwrap()->yield($context);
        // line 11
        yield "</div>";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "storefront/my-account/review-form.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable()
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo()
    {
        return array (  60 => 11,  57 => 10,  55 => 9,  43 => 2,  40 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "storefront/my-account/review-form.twig", "/var/www/html/wp-content/plugins/reviewx/resources/views/storefront/my-account/review-form.twig");
    }
}
