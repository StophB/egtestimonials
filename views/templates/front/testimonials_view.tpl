

{extends file='page.tpl'}
{block name='page_header_container'}
<h2>Testimonials</h2>
{/block}

{block name='page_content'}

    <form action ='{$action_url}' method ='post' id="testimonial-form" enctype="multipart/form-data" >
        <div class="form-group">
            <label for="Title">Title</label>
            <input class="form-control form-control-lg" type="text" placeholder="Your title" id="title" name='title'>
        </div>
        <div class="form-group">
            <label for="Image">Image</label>
            <input type="file" class="form-control-file" id='file' name='file'>
        </div>
        <div class="form-group">
            <label for="Message">Message</label>
            <textarea class="form-control" rows="3" id='message' name='message'></textarea>
        </div>
        <button type="submit" class="btn btn-warning ">Add New Testimonial</button>
    </form>
    <h1 class="mt-2">Testimonials</h1>
    
    {if isset($datas) && !empty($datas)}
        <div class="row">  
            {foreach from=$datas item=data}
                <div class="col">
                    <img src="{$path}{$data.file}"  class="ts-image" >
                    <h5>{$data.firstname }</h5>
                    <p>Testimonial : {$data.message}</p>
                </div> 
            {/foreach} 
        </div>
    {/if}

   

   
{/block}