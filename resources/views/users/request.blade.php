@extends('layout.default')

@section('content')
<style type="text/css">
    .panel-body > div {
        margin-bottom: 10px;
    }
</style>
<div id="admin-container">    
    <div class="admin-section-title">
        <h3><i class="entypo-down-circled"></i> Importar Cliente</h3> 
    </div>
    <div class="clear"></div>
        <form method="POST" action="/dashboard/users/send_request">
        <div>
        <h4>• Todas las contraseñas de los clientes de el canal anterior fueron cambiadas a <b>1234</b>. Para entrar al CloudTV nuevo, sus usuarios deben de usar su <i>Nombre de Usuario</i> y <b>1234</b> como <i>Contraseña</i>.</h4> 
        <h4>• Es necesario que el cliente haya entrado a la aplicacion de CloudTV nueva para poder importarlo a tu lista de <i>Mis Clientes</i>.</h4>    
    	<h4>• Esta herramienta fue diseñada para importar clientes a tu lista de "Mis Usuarios". Solamente funciona con clientes importados del canal anterior que no tengan a un admin asignado.</h4>
    	<h4>• Para poder importar a un cliente correctamente, sera necesario que:</h4><br>
                    <p>1. El cliente ya haya entrado a el canal de CloudTV nuevo.<br>
                    2. Que el <i>Nombre de Usuario</i> sea correcto.<br><br>
                    </p>
                    </div>
               
               
               
               
                    
                    
            <div class="panel panel-info" data-collapsed="0"> 
            	<div class="panel-heading"> 
                		<div class="panel-title">
                    	Importar cliente 
                		</div> 
                	<div class="panel-options"> <a href="#" data-rel="collapse"></a> 
                	</div>
                </div> 
                	<div class="panel-body" style="display: block;">
                    <div>
                        <p>Nombre de usuario</p>
                        <input type="text" class="form-control" name="username"/>
                    </div>
                </div>
            </div>
            
            
            
            
            
            
           <div class="panel-group" id="accordion">
  <div class="panel panel-danger">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
        Area de Resolucion de Problemas</a>
      </h4>
    </div>
    <div id="collapse1" class="panel-collapse collapse in">
    </div>
  </div>
  <div class="panel panel-success">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
        Tengo clientes con subscripcion activa que quisiera traerme a este canal/panel, como le hago?</a>
      </h4>
    </div>
    <div id="collapse2" class="panel-collapse collapse">
      <div class="panel-body">El cliente puede entrar con su <i>Nombre de Usuario</i> y <b>1234</b> como <i>Contraseña</i>
                        Una vez que el cliente haya entrado al canal nuevo, sera registrado en el nuevo panel
                    	pero no sabremos a que admin pertenece. Es por eso que existe esta herramienta. Introduce su <i>Nombre de Usuario</i> en el campo de arriba para importarlo
                    	a <i>Mis Clientes</i>.</div>
    </div>
  </div>
  <div class="panel panel-success">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
        Mi cliente no puede entrar a el canal nuevo con su mismo <i>Nombre de Usuario</i> y <i>Contraseña</i>, que pasa?</a>
      </h4>
    </div>
    <div id="collapse3" class="panel-collapse collapse">
      <div class="panel-body">1. Que la cuenta no este expirada en el panel anterior. (Esta herramienta es para importar clientes con cuentas activas)<br>
                    2. Que el <i>Nombre de Usuario</i> y <i>Contraseña (1234)</i> sean correctos.<br>
                    3. Que sea el mismo <i>MAC Address (Mismo Aparato)</i> de donde se conectaba a el canal anterior.<br>
                    4. Pidele a tu cliente que haga una <i>Actualizacion de Sistema/System Update en los ajustes de Roku</i>.<br>
                   		 5. Si el cliente esta EXPIRADO en el panel anterior, no te permitira importarlo ya que es logico crearle una cuenta nueva directamente.</div>
    </div>
  </div>
</div>
            
            
            <br>
            {{csrf_field()}}
            <input type="submit" value="Request" class="btn btn-success pull-right" />
        </form>

        <div class="clear"></div>
<!-- This is where now -->
</div>
@endsection