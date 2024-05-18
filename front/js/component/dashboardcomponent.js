//Representaria la lisat de switches
class DashboardComponent extends Fronty.ModelComponent {
    constructor(dashboardModel, userModel, router) {
        super(Handlebars.templates.dashboard, dashboardModel, null, null);
        
        
        this.dashboardModel = dashboardModel;
        this.userModel = userModel;
        this.addModel('user', userModel);
        this.router = router;

        this.switchsService = new SwitchsService();
        this.suscriptionsService = new SuscriptionsService();

    }

    onStart() {
        this.updateSwitchs();
        this.updateSuscriptions();
    }

    //Recarga la lista de switches
    updateSwitchs() {
        //Enviamos peticion rest a traves de Switchsservice para recuperar todos los switches
        this.switchsService.findAllSwitchs().then((data) => {

        //Actualizamos el modelo de la lista de switches con la lista recibida
        this.dashboardModel.setSwitchs(
            // create a Fronty.Model for each item retrieved from the backend
            data.map(
            (item) => new SwitchModel(item.id, item.public_id, item.private_id, item.nombre, item.descripcion, item.owner, item.auto_off_time, item.last_time, item.status)
        ));

        });

    }

    //Recarga la lista de suscripciones
    updateSuscriptions() {
        //Enviamos peticion rest a traves de Suscriptionsservice para recuperar todas las suscripciones del usuario
        this.suscriptionsService.findAllSuscriptions().then((data) => {
        

        //Actualizamos el modelo de la lista de suscripciones con la lista recibida
        this.dashboardModel.setSuscriptions(
            // create a Fronty.Model for each item retrieved from the backend
            data.map((item) => new SuscriptionModel(item.id, item.switch_id,  
                                    item.switch_name, 
                                    item.switch_description, 
                                    item.switch_owner, 
                                    item.switch_auto_off_time, 
                                    item.switch_last_time, 
                                    item.switch_status,
                                    item.username)
        ));

        });

    }

    // Override
    createChildModelComponent(className, element, id, modelItem) {
        if (className == "Switch") {
            return new DashboardSwitchComponent(modelItem, this.userModel, this.router, this);
        } else if (className == "Suscription") {
            return new DashboardSuscriptionComponent(modelItem, this.userModel, this.router, this);
        }
    }
}
  

//Representa a una fila o un switch dentro del dashboard
class DashboardSwitchComponent extends Fronty.ModelComponent {
    constructor(switchModel, userModel, router, dashboardComponent) {
        super(Handlebars.templates.switchrow, switchModel, null, null);
        
        this.dashboardComponent = dashboardComponent;
        
        this.userModel = userModel;
        this.addModel('user', userModel); // a secondary model
        
        this.router = router;

        // Event listener para encender el switch
        this.addEventListener('click', '#btn-encender', (event) => {
            this.encenderSwitch(event);
        });
    
        // Event listener para apagar el switch
        this.addEventListener('click', '#btn-apagar', (event) => {
            this.apagarSwitch(event);
        });

    }

    // Event listener para encender el switch
    encenderSwitch(event) {
        //La petición REST para actualizar un switch solo necesita el tiempo y la fecha
        var newSwitch = {};
        newSwitch.private_id = event.target.getAttribute('item');
        newSwitch.auto_off_time = 10;
        newSwitch.last_time = this.getFechaHoraActual();

        //Enviamos petición REST a update a través de SwitchService
        this.dashboardComponent.switchsService.updateSwitch(newSwitch)
        .fail(() => {
            alert('switch cannot be turned on');
        })
        .always(() => {
            this.dashboardComponent.updateSwitchs();
        });
    }

    // Event listener para apagar el switch
    apagarSwitch(event) {
        //La petición REST para actualizar un switch solo necesita el tiempo y la fecha
        var newSwitch = {};
        newSwitch.private_id = event.target.getAttribute('item');
        newSwitch.auto_off_time = 0;

        //Enviamos petición REST a update a través de SwitchService
        this.dashboardComponent.switchsService.updateSwitch(newSwitch)
        .fail(() => {
            alert('switch cannot be turned off');
        })
        .always(() => {
            this.dashboardComponent.updateSwitchs();
        });
    }

    getFechaHoraActual() {
        const fechaHoraActual = new Date();
        const day = fechaHoraActual.getDate().toString().padStart(2, '0');
        const month = (fechaHoraActual.getMonth() + 1).toString().padStart(2, '0'); // Los meses comienzan desde 0
        const year = fechaHoraActual.getFullYear();
        const hour = fechaHoraActual.getHours().toString().padStart(2, '0');
        const minutes = fechaHoraActual.getMinutes().toString().padStart(2, '0');
        const seconds = fechaHoraActual.getSeconds().toString().padStart(2, '0');
        return `${year}-${month}-${day} ${hour}:${minutes}:${seconds}`;
    }  
}


//Representa a una fila o una suscripcion en el dashboard
class DashboardSuscriptionComponent extends Fronty.ModelComponent {
    constructor(suscriptionModel, userModel, router, dashboardComponent) {
        super(Handlebars.templates.dashboardsuscription, suscriptionModel, null, null);
        
        this.dashboardComponent = dashboardComponent;
        
        this.userModel = userModel;
        this.addModel('user', userModel); // a secondary model
        
        this.router = router;
    }
}
  