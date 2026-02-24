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

/* storefront/widget/Features/ReviewForm/MediaUpload.twig */
class __TwigTemplate_7a7d50850991aa37ac79559053881be7 extends Template
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
        yield "<div x-data=\"__rvxMediaUploadComponent__()\" 
@notify-review-added.window=\"clearAll\" class=\"rvx-flex rvx-flex-wrap rvx-gap-space16\">
    <label for=\"add-review-upload-media\"
           class=\"rvx-block rvx-py-[11px] rvx-px-[9px] rvx-rounded-md rvx-cursor-pointer rvx-text-center rvx-box-border rvx-self-baseline rvx-review-form__attachment--upload rvx-border-2 rvx-border-solid rvx-border-transparent\"
           :class=\"`\${isDragging ? 'rvx-bg-neutral-100 !rvx-border-neutral-600 rvx-border-dashed' : 'rvx-bg-neutral-200'}`\"
           @dragenter.prevent=\"dragEnterHandler\"
           @dragover.prevent=\"dragEnterHandler\"
           @dragleave.prevent=\"dragLeaveHandler\"
           @drop.prevent=\"dropHandler\">

        <input type=\"file\" @change=\"onUpload\" :multiple=\"multiple\" :accept=\"accept\"
               :disabled=\"disabled\"
               id=\"add-review-upload-media\" class=\"!rvx-hidden\">
        <span class=\"material-symbols-rounded rvx-block rvx-mb-space5 rvx-review-form__attachment--upload--icon\">";
        // line 14
        yield $this->env->getRuntime('Rvx\Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("backup", "reviewx"), "html", null, true);
        yield "</span>
        
        <span x-show=\"ready && (isPhotoReviewsAllowed || isVideoReviewsAllowed)\"
            class=\"!rvx-text-[12px] rvx-block rvx-review-form__attachment--upload--text !rvx-font-nunito\">
            <span x-show=\"isPhotoReviewsAllowed && !isVideoReviewsAllowed\">
                ";
        // line 19
        yield $this->env->getRuntime('Rvx\Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("Upload Photo", "reviewx"), "html", null, true);
        yield "
            </span>
            <span x-show=\"isVideoReviewsAllowed && !isPhotoReviewsAllowed\">
                ";
        // line 22
        yield $this->env->getRuntime('Rvx\Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("Upload Video", "reviewx"), "html", null, true);
        yield "
            </span>
            <span x-show=\"isPhotoReviewsAllowed && isVideoReviewsAllowed\">
                ";
        // line 25
        yield $this->env->getRuntime('Rvx\Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("Upload Photo / Video", "reviewx"), "html", null, true);
        yield "
            </span>
        </span>

        <span class=\"!rvx-text-[12px] !rvx-font-nunito rvx-block rvx-review-form__attachment--upload--count\">
            <span x-text=\"files.urls.length\"></span>/<span x-text=\"maxFileCount\"></span>
        </span>
    
    </label>

    <p x-show=\"maxFileCountError\" class=\"rvx-text-[12px] rvx-text-danger !rvx-font-nunito\" x-text=\"maxFileCountError\"></p>
    <p x-show=\"maxFileSizeError\" class=\"rvx-text-[12px] rvx-text-danger !rvx-font-nunito\" x-text=\"maxFileSizeError\"></p>
    
    <template x-for=\"(file, index) in files.urls\">
        <div class=\"rvx-relative\">
            <div x-show=\"file.type === 'image'\">
                <img class=\"!rvx-size-[80px] rvx-rounded-md rvx-object-cover rvx-object-top\" :src=\"file.path ?? ''\" alt=\"uploaded images\"/>
            </div>
            <div x-show=\"file.type === 'video'\">
                <video :src=\"file.path\" class=\"!rvx-size-[80px] rvx-rounded-md rvx-object-cover rvx-object-top\"></video>
<!--                <img  :src=\"file.path ?? ''\" alt=\"uploaded images\"/>-->
            </div>
            <span @click=\"remove(index)\" class=\"material-symbols-rounded rvx-absolute rvx-top-0 rvx-right-0 rvx-cursor-pointer rvx-text-danger-700 rvx-bg-white hover:rvx-bg-danger-50 rvx-rounded-[4px] !rvx-text-[20px]\"> ";
        // line 47
        yield $this->env->getRuntime('Rvx\Twig\Runtime\EscaperRuntime')->escape($this->env->getFunction('__')->getCallable()("delete", "reviewx"), "html", null, true);
        yield "</span>
        </div>
    </template>

</div>";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "storefront/widget/Features/ReviewForm/MediaUpload.twig";
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
        return array (  100 => 47,  75 => 25,  69 => 22,  63 => 19,  55 => 14,  40 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "storefront/widget/Features/ReviewForm/MediaUpload.twig", "/var/www/html/wp-content/plugins/reviewx/resources/views/storefront/widget/Features/ReviewForm/MediaUpload.twig");
    }
}
