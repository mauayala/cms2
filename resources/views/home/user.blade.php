@extends('layouts.landing')

@section('content')
<div class="container user">

	@if(isset($type) && $type == 'profile')

		<div id="user-badge">
			<img src="/avatars/{{ $user->avatar }}" />
			<h2 class="form-signin-heading">{{ $user->username }}</h2>
			<div class="label label-info">{{ ucfirst($user->role) }} User</div>
			<p class="member-since">Member since: {{ $user->created_at }}</p>

			@if(!Auth::guest() && Auth::user()->username == $user->username)
				<a href="/users/edit/{{ $user->username }}" class="btn btn-info"><i class="fa fa-edit"></i> Edit</a>
			@endif
		</div>

		<h2>{{ ucfirst($user->username) }}'s Favorites </h2>
		<div class="heading-divider"></div>
		<div class="row">

			@each('partials/video-loop', $videos, 'video')

			<div class="clear"></div>
			<a class="user-favorites" href="#">View All Favorites</a>
		</div>


	@elseif(isset($type) && $type == 'edit')

		<h4 class="subheadline"><i class="fa fa-edit"></i> Update Your Profile Info</h4>
		<div class="clear"></div>

		<form method="POST" action="<?= $post_route ?>" id="update_profile_form" accept-charset="UTF-8" file="1" enctype="multipart/form-data">
			@csrf
			<div id="user-badge">
				<img src="<?= Config::get('site.uploads_url') . 'avatars/' . $user->avatar ?>" />
				<label for="avatar">Avatar</label>
				<input type="file" multiple="true" class="form-control" name="avatar" id="avatar" />
			</div>

			<div class="well">
				<?php if($errors->first('username')): ?><div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> <strong>Oh snap!</strong> <?= $errors->first('username'); ?></div><?php endif; ?>
				<label for="username">Username</label>
				<input type="text" class="form-control" name="username" id="username" value="<?php if(!empty($user->username)): ?><?= $user->username ?><?php endif; ?>" />
			</div>

			<div class="well">
				<?php if($errors->first('email')): ?><div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> <strong>Oh snap!</strong> <?= $errors->first('email'); ?></div><?php endif; ?>
				<label for="email">Email</label>
				<input type="text" class="form-control" name="email" id="email" value="<?php if(!empty($user->email)): ?><?= $user->email ?><?php endif; ?>" />
			</div>

			<div class="well">
				<label for="password">Password (leave empty to keep your original password)</label>
				<input type="password" class="form-control" name="password" id="password" value="" />
			</div>

			<?php if(($settings->free_registration && $settings->premium_upgrade) || (!$settings->free_registration)): ?>
				<div class="well">
					<label for="role" style="margin-bottom:10px;">User Type</label>
					<?php if($user->role == 'subscriber'): ?>
						<div class="label label-success"><i class="fa fa-certificate"></i> <?= ucfirst($user->role) ?> User</div>
						<div class="clear"></div>
					<?php elseif($user->role == 'registered'): ?>
						<div class="label label-warning"><i class="fa fa-user"></i> <?= ucfirst($user->role) ?> User</div>
						<div class="clear"></div>
					<?php elseif($user->role == 'demo'): ?>
						<div class="label label-danger"><i class="fa fa-life-saver"></i> <?= ucfirst($user->role) ?> User</div>
						<div class="clear"></div>
					<?php elseif($user->role == 'admin'): ?>
						<div class="label label-primary"><i class="fa fa-star"></i> <?= ucfirst($user->role) ?> User</div>
						<div class="clear"></div>
					<?php endif; ?>
					<?php if($settings->free_registration && $settings->premium_upgrade): ?>
						<a class="btn btn-primary" href="<?= ($settings->enable_https) ? secure_url('/') : URL::to('user') ?><?= '/' . $user->username; ?>/upgrade_subscription" style="margin-top:10px;"><i class="fa fa-certificate"></i> Upgrade to Premium Subscription</a>
					<?php else: ?>
						<a class="btn btn-primary" href="<?= ($settings->enable_https) ? secure_url('/') : URL::to('user') ?><?= '/' . $user->username; ?>/billing" style="margin-top:10px;"><i class="fa fa-credit-card"></i> Manage Your Billing Info</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<input type="submit" value="Update Profile" class="btn btn-primary" />

			<div class="clear"></div>
		</form>

	@elseif(isset($type) && $type == 'billing')

		<?php include('partials/user-billing.php'); ?>

	@elseif(isset($type) && $type == 'update_credit_card')

		<?php include('partials/user-update-billing.php'); ?>

	@elseif(isset($type) && $type == 'renew_subscription')

		<?php include('partials/renew-subscription.php'); ?>

	@elseif(isset($type) && $type == 'upgrade_subscription')

		<?php include('partials/upgrade-subscription.php'); ?>

	@endif
</div>

@endsection