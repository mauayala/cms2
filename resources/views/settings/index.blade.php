@extends('layout.default')

@section('css')
	<style type="text/css">
	.make-switch{
		z-index:2;
	}
	</style>
@endsection

@section('content')


<div id="admin-container">
<!-- This is where -->
	
	<div class="admin-section-title">
		<h3><i class="entypo-globe"></i> Ajustes de CloudTV</h3> 
	</div>
	<div class="clear"></div>

	

	<form method="POST" action="/dashboard/settings/update" accept-charset="UTF-8" file="1" enctype="multipart/form-data">	
		<div class="row">
			
			<div class="col-md-4">
				<div class="panel panel-primary" data-collapsed="0"> <div class="panel-heading"> 
					<div class="panel-title">Nombre de Canal</div> <div class="panel-options"> <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a> </div></div> 
					<div class="panel-body" style="display: block;"> 
						<p>Introduce el Nombre de el Negocio a continuacion:</p> 
						<input type="text" class="form-control" name="website_name" id="website_name" placeholder="CloudTV, Zulu" value="@if(!empty($settings->website_name)){{ $settings->website_name }}@endif" />
					</div> 
				</div>
			</div>

			<div class="col-md-8">
				<div class="panel panel-primary" data-collapsed="0"> <div class="panel-heading"> 
					<div class="panel-title">Descripcion de Negocio</div> <div class="panel-options"> <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a> </div></div> 
					<div class="panel-body" style="display: block;"> 
						<p>Introduce la descripcion del negocio a continuacion:</p> 
						<input type="text" class="form-control" name="website_description" id="website_description" placeholder="CTV, ZULU" value="@if(!empty($settings->website_description)){{ $settings->website_description }}@endif" />
					</div> 
				</div>
			</div>

		</div>

		<div class="panel panel-primary" data-collapsed="0"> <div class="panel-heading"> 
			<div class="panel-title">Logo</div> <div class="panel-options"> <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a> </div></div> 
			<div class="panel-body" style="display: block; background:#f1f1f1;"> 
				@if(!empty($settings->logo))
					<img src="/settings/{{ $settings->logo }}" />
				@endif
				<p>Selecciona el logo del canal:</p> 
				<input type="file" multiple="true" class="form-control" name="logo" id="logo" />
				
			</div> 
		</div>

		<div class="panel panel-primary" data-collapsed="0"> <div class="panel-heading"> 
			<div class="panel-title">Login Background</div> <div class="panel-options"> <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a> </div></div> 
			<div class="panel-body" style="display: block; background:#f1f1f1;"> 
				@if(!empty($settings->login_background))
					<img src="{{ $settings->login_background }}" />
				@endif
				<p>Selecciona el login background del canal:</p> 
				<input type="file" class="form-control" name="login_background" id="login_background" />
				
			</div> 
		</div>

		<div class="panel panel-primary" data-collapsed="0"> <div class="panel-heading"> 
			<div class="panel-title">Favicon</div> <div class="panel-options"> <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a> </div></div> 
			<div class="panel-body" style="display: block;"> 
				@if(!empty($settings->favicon))
					<img src="/settings/{{ $settings->favicon }}" style="max-height:20px" />
				@endif
				<p>Selecciona el favicon del logo:</p> 
				<input type="file" multiple="true" class="form-control" name="favicon" id="favicon" />
				
			</div> 
		</div>

		<div class="panel panel-primary" data-collapsed="0"> <div class="panel-heading"> 
			<div class="panel-title">System Email</div> <div class="panel-options"> <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a> </div></div> 
			<div class="panel-body" style="display: block;"> 
				<p>Email address to be used to send system emails:</p> 
				<input type="text" class="form-control" name="system_email" id="system_email" placeholder="Email Address" value="@if(!empty($settings->system_email)){{ $settings->system_email }}@endif" />
			</div> 
		</div>

		<div class="panel panel-primary" data-collapsed="true"> <div class="panel-heading"> 
			<div class="panel-title">Social Networks</div> <div class="panel-options"> <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a> </div></div> 
			<div class="panel-body" style="display: block;"> 
				
				<p>Facebook Page ID: ex. facebook.com/page_id (without facebook.com):</p> 
				<input type="text" class="form-control" name="facebook_page_id" id="facebook_page_id" placeholder="Facebook Page" value="@if(!empty($settings->facebook_page_id)){{ $settings->facebook_page_id }}@endif" />
				<br />
				<p>Google Plus User ID:</p>
				<input type="text" class="form-control" name="google_page_id" id="google_page_id" placeholder="Google Plus Page" value="@if(!empty($settings->google_page_id)){{ $settings->google_page_id }}@endif" />
				<br />
				<p>Twitter Username:</p>
				<input type="text" class="form-control" name="twitter_page_id" id="twitter_page_id" placeholder="Twitter Username" value="@if(!empty($settings->twitter_page_id)){{ $settings->twitter_page_id }}@endif" />
				<br />
				<p>YouTube Channel ex. youtube.com/channel_name:</p>
				<input type="text" class="form-control" name="youtube_page_id" id="youtube_page_id" placeholder="YouTube Channel" value="@if(!empty($settings->youtube_page_id)){{ $settings->youtube_page_id }}@endif" />
			
			</div> 
		</div>

		<div class="panel panel-primary" data-collapsed="0"> <div class="panel-heading"> 
			<div class="panel-title">Google Analytics Tracking ID</div> <div class="panel-options"> <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a> </div></div> 
			<div class="panel-body" style="display: block;"> 
				
				<p>Google Analytics Tracking ID (ex. UA-12345678-9)::</p> 
				<input type="text" class="form-control" name="google_tracking_id" id="google_tracking_id" placeholder="Google Analytics Tracking ID" value="@if(!empty($settings->google_tracking_id)){{ $settings->google_tracking_id }}@endif" />
				
			</div> 
		</div>

		<div class="panel panel-primary" data-collapsed="0"> <div class="panel-heading"> 
			<div class="panel-title">Google Analytics API Integration (API_Integracion_Dashboard)</div> <div class="panel-options"> <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a> </div></div> 
			<div class="panel-body" style="display: block;"> 
				
				<p>Google Oauth Client ID Key:</p> 
				<input type="text" class="form-control" name="google_oauth_key" id="google_oauth_key" placeholder="Google Client ID Key" value="@if(!empty($settings->google_oauth_key)){{ $settings->google_oauth_key }}@endif" />
				

			</div> 
		</div>

		<div class="panel panel-primary" data-collapsed="0"> <div class="panel-heading"> 
			<div class="panel-title">Ligas</div> <div class="panel-options"> <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a> </div></div> 
			<div class="panel-body" style="display: block;"> 
				<p>Ruta a VOD:</p> 
				<input type="text" class="form-control" name="server_link" id="server_link" placeholder="Server Link" value="@if(!empty($settings->server_link)){{ $settings->server_link }}@endif" />
				<p>Ruta a Subtitulos:</p> 
				<input type="text" class="form-control" name="subtitle_link" id="subtitle_link" placeholder="Subtitle Link" value="@if(!empty($settings->subtitle_link)){{ $settings->subtitle_link }}@endif" />
				<p>Ruta a XML (EPG):</p> 
				<input type="text" class="form-control" name="xml_link" id="xml_link" placeholder="XML Link" value="@if(!empty($settings->xml_link)){{ $settings->xml_link }}@endif" />
				<p>Ruta a Trailer:</p> 
				<input type="text" class="form-control" name="trailer_link" id="trailer_link" placeholder="Trailer Link" value="@if(!empty($settings->trailer_link)){{ $settings->trailer_link }}@endif" />
			</div> 
		</div>

		<div class="panel panel-primary" data-collapsed="0"> 
			<div class="panel-heading"> 
				<div class="panel-title">Live Settings</div> 
				<div class="panel-options"> 
					<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a> 
				</div>
			</div> 
			<div class="panel-body" style="display: block;"> 
				<p>IP/Domain:</p> 
				<input type="text" class="form-control" name="ip_domain" id="ip_domain" placeholder="IP or Domain" value="@if(!empty($settings->ip_domain)){{ $settings->ip_domain }}@endif" />
				<p>Live Username:</p> 
				<input type="text" class="form-control" name="live_username" id="live_username" placeholder="Live Username" value="@if(!empty($settings->live_username)){{ $settings->live_username }}@endif" />
				<p>Live Password:</p> 
				<input type="text" class="form-control" name="live_password" id="live_password" placeholder="Live Password" value="@if(!empty($settings->live_password)){{ $settings->live_password }}@endif" />
			</div> 
		</div>
		{{ csrf_field() }}
		<input type="submit" value="Update Settings" class="btn btn-success pull-right" />
	</form>

	<div class="clear"></div>

	@if(env('APP_ENV') == 'development')
		<a href="/dashboard/settings/git/to_master" class="btn btn-danger">Merge from Dev to Master and push to production</a>
	@endif

</div><!-- admin-container -->

	@section('javascript')
		<script type="text/javascript">

			$ = jQuery;

			$(document).ready(function(){

				$('input[type="checkbox"]').change(function() {
					if($(this).is(":checked")) {
				    	$(this).val(1);
				    } else {
				    	$(this).val(0);
				    }
				});

				$('#free_registration').change(function(){
					if($(this).is(":checked")) {
						$('#activation_email_block').fadeIn();
						$('#premium_upgrade_block').fadeIn();
					} else {
						$('#activation_email_block').fadeOut();
						$('#premium_upgrade_block').fadeOut();
					}
				});

			});

		</script>

	@endsection

@endsection