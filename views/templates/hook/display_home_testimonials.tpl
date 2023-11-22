
<div>

{if isset($datas) && !empty($datas)}
<a class="col-lg-4 col-md-6 col-sm-6 col-xs-12"  href="{$testomonial_url}">
    <span class="link-item">
       {l s='Testimonials' d='Module.Testimonials.Testimonials'}
    </span>
</a> <br>
    <div>
    {foreach from=$datas item=data}
        <p> Testimonial : {$data.message} </p>
        <p>{$data.firstname }</p>
    {/foreach} 
    </div>
{/if}

</div>