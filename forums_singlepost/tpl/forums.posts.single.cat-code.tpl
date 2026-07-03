<!-- BEGIN: MAIN -->
<!-- forums.posts.single.cat-code.tpl -->
<div class="container my-5">
	
	<h1 class="h4">{FORUMS_POSTS_TITLE}</h1>
	
	<!-- IF {FORUMS_POSTS_TOPICDESC} -->
	<h2 class="h6 mb-4">{FORUMS_POSTS_TOPICDESC}</h2>
	<!-- ENDIF -->
	
	<!-- BEGIN: FORUMS_POSTS_ROW -->
	<article class="single-post">
		<h1 class="h4">{FORUMS_POSTS_ROW_USER_NAME}</h1>
		<div class="text-muted mb-3">
			#{FORUMS_POSTS_ROW_ORDER} &bull; {FORUMS_POSTS_ROW_CREATION}
		</div>
		<div class="mb-3"  id="protected-block" >
			{FORUMS_POSTS_ROW_TEXT}
			
			<!-- IF {FORUMS_POSTS_ROW_FORUMPOST_LINK_EXTERNAL} -->
			<div class="mb-3">							
				<button class="btn btn-lg btn-info" onclick="openLink()">{FORUMS_POSTS_ROW_FORUMPOST_LINK_EXTERNAL_TITLE}</button>
				
				<script>
					function openLink() {
						window.open('{FORUMS_POSTS_ROW_FORUMPOST_LINK_EXTERNAL}', '_blank');
					}
				</script>
			</div>
			<!-- ENDIF -->
			
		</div>
	</article>
	<!-- END: FORUMS_POSTS_ROW -->
	
	<!-- BEGIN: FORUMS_POSTS_NEWPOST -->
	<form action="{FORUMS_POSTS_NEWPOST_SEND}" method="post" name="newpost" class="card border-primary mt-5">
		<div class="card-header fw-bold">
			{PHP.L.Reply}
		</div>
		<div class="card-body">
			<div class="mb-3 form-floating">
				{FORUMS_POSTS_NEWPOST_TEXT}
			</div>
			
			<div class="d-flex flex-wrap gap-3 align-items-center">
				<!-- IF {FORUMS_POSTS_NEWPOST_PFS} -->
				{FORUMS_POSTS_NEWPOST_PFS}
				<!-- ENDIF -->
				
				<!-- IF {FORUMS_POSTS_NEWPOST_SFS} -->
				<!-- IF {FORUMS_POSTS_NEWPOST_PFS} --><span class="align-self-center mx-2">{PHP.cfg.separator}</span><!-- ENDIF -->
				{FORUMS_POSTS_NEWPOST_SFS}
				<!-- ENDIF -->
				
				<!-- IF {PHP.cfg.forums.edittimeout} != 0 -->
				<div class="text-muted small ms-auto">
					{PHP.L.forums_edittimeoutnote} <strong>{FORUMS_POSTS_NEWPOST_EDITTIMEOUT}</strong>
				</div>
				<!-- ENDIF -->
			</div>
			

			<hr>
			<!-- IF {FORUMS_POSTS_NEWPOST_FORUMPOST_LINK_EXTERNAL} -->
			
				<!-- IF {FORUMS_POSTS_NEWPOST_FORUMPOST_LINK_EXTERNAL_TITLE} -->
				<span class="mx-2">{FORUMS_POSTS_NEWPOST_FORUMPOST_LINK_EXTERNAL_TITLE}</span>
				<!-- ENDIF -->
			
			{FORUMS_POSTS_NEWPOST_FORUMPOST_LINK_EXTERNAL}
			<!-- ENDIF -->

		</div>
		
		<div class="card-footer text-end">
			<button type="submit" class="btn btn-primary px-5">
				<i class="fa-solid fa-paper-plane me-2"></i>{PHP.L.Reply}
			</button>
		</div>
	</form>
	<!-- END: FORUMS_POSTS_NEWPOST -->
</div>
<!-- END: MAIN -->
