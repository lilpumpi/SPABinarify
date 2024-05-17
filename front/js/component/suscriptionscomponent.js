//Representaria la lisat de switches
class SuscriptionsComponent extends Fronty.ModelComponent {
    constructor(suscriptionsModel, userModel, router) {
        super(Handlebars.templates.suscriptionslist, suscriptionsModel, null, null);
        
        
        this.suscriptionsModel = suscriptionsModel;
        this.userModel = userModel;
        this.addModel('user', userModel);
        this.router = router;

        this.switchsService = new SwitchsService();
        this.suscriptionsService = new SuscriptionsService();


    }

    onStart() {
        this.updateSuscriptions();
    }

    //Recarga la lista de suscripciones
    updateSuscriptions() {
        //Enviamos peticion rest a traves de Suscriptionsservice para recuperar todas las suscripciones del usuario
        this.suscriptionsService.findAllSuscriptions().then((data) => {
        

        //Actualizamos el modelo de la lista de suscripciones con la lista recibida
        this.suscriptionsModel.setSuscriptions(
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
        return new SuscriptionRowComponent(modelItem, this.userModel, this.router, this);
    }
}
  

//Representa a una fila o una suscripcion
class SuscriptionRowComponent extends Fronty.ModelComponent {
    constructor(suscriptionModel, userModel, router, suscriptionsComponent) {
        super(Handlebars.templates.suscriptionitem, suscriptionModel, null, null);
        
        this.suscriptionsComponent = suscriptionsComponent;
        
        this.userModel = userModel;
        this.addModel('user', userModel); // a secondary model
        
        this.router = router;

        //Se añadirá un event listener para eliminar la suscripcion
        this.addEventListener('click', '.btn-suscribe', (event) => {
            var suscriptionId = event.target.getAttribute('item');
            this.suscriptionsComponent.suscriptionsService.deleteSuscription(suscriptionId) //Enviamos peticion rest delete a traves de Switchsservice
            .fail(() => {
                alert('suscription cannot be deleted')
            })
            .always(() => {
                this.suscriptionsComponent.updateSuscriptions(); //Una vez eliminado la suscripcion, actualizamos la lista
            });
        });

    }
}
  