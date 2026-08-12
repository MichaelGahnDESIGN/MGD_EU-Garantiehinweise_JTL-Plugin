<section {$instance->getAttributeString()} class="mgd-eu-hinweis {$instance->getStyleClasses()}" aria-labelledby="mgd-eu-hinweis-titel-{$instance->getUid()}">
    <h2 id="mgd-eu-hinweis-titel-{$instance->getUid()}">{$instance->getProperty('title')|escape:'html'}</h2>
    <div class="mgd-eu-hinweis__text">{$instance->getProperty('text')}</div>
    {if $instance->safeLinkUrl !== ''}
        <a class="btn btn-primary" href="{$instance->safeLinkUrl|escape:'html'}">{$instance->getProperty('link-label')|escape:'html'}</a>
    {/if}
</section>
