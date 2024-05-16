//Representaria la lisat de switches
class SwitchsComponent extends Fronty.ModelComponent {
    constructor(switchsModel, userModel, router) {
        super(Handlebars.templates.switchstable, switchsModel, null, null);
        
        
        this.switchsModel = switchsModel;
        this.userModel = userModel;
        this.addModel('user', userModel);
        this.router = router;

        this.switchsService = new SwitchsService();

    }

    onStart() {
        this.updateSwitchs();
    }

    //Recarga la lista de switches
    updateSwitchs() {
        //Enviamos peticion rest a traves de Switchsservice para recuperar todos los switches
        this.switchsService.findAllSwitchs().then((data) => {

        //Actualizamos el modelo de la lista de switches con la lista recibida
        this.switchsModel.setSwitchs(
            // create a Fronty.Model for each item retrieved from the backend
            data.map(
            (item) => new SwitchModel(item.id, item.public_id, item.private_id, item.nombre, item.descripcion, item.owner, item.auto_off_time, item.last_time, item.status)
        ));

        });

    }

    // Override
    createChildModelComponent(className, element, id, modelItem) {
        return new SwitchRowComponent(modelItem, this.userModel, this.router, this);
    }
}
  

//Representa a una fila o un switch
class SwitchRowComponent extends Fronty.ModelComponent {
    constructor(switchModel, userModel, router, switchsComponent) {
        super(Handlebars.templates.switchrow, switchModel, null, null);
        
        this.switchsComponent = switchsComponent;
        
        this.userModel = userModel;
        this.addModel('user', userModel); // a secondary model
        
        this.router = router;

        //Se añadirá un event listener para eliminar el switch (depende del html)
        this.addEventListener('click', '.eliminar', (event) => {
            var switchId = event.target.getAttribute('item');
            this.switchsComponent.switchsService.deleteSwitch(switchId) //Enviamos peticion rest delete a traves de Switchsservice
            .fail(() => {
                alert('switch cannot be deleted')
            })
            .always(() => {
                this.switchsComponent.updateSwitchs(); //Una vez eliminado el switch, actualizamos la lista
            });
        });


        //Se añadirá un event listener para encender el switch
        this.addEventListener('click', '#btn-encender', (event) => {
            //La peticion rest para actualizar un switch solo necesita el tiempo y la fecha
            var newSwitch = {};
            newSwitch.id = event.target.getAttribute('item');
            newSwitch.auto_off_time = 10;
            newSwitch.last_time = getFechaHoraActual();

             //Enviamos peticion rest a update a traves de SwitchService
            this.switchsComponent.switchsService.updateSwitch(newSwitch)
            .fail(() => {
                alert('switch cannot be turned on')
            })
            .always(() => {
                this.switchsComponent.updateSwitchs(); //Una vez encendido el switch, actualizamos la lista
            });
        });


        //Se añadirá un event listener para apagar el switch
        this.addEventListener('click', '#btn-apagar', (event) => {
            //La peticion rest para actualizar un switch solo necesita el tiempo y la fecha
            var newSwitch = {};
            newSwitch.id = event.target.getAttribute('item');
            newSwitch.auto_off_time = 0;

             //Enviamos peticion rest a update a traves de SwitchService
            this.switchsComponent.switchsService.updateSwitch(newSwitch)
            .fail(() => {
                alert('switch cannot be turned off')
            })
            .always(() => {
                this.switchsComponent.updateSwitchs(); //Una vez apagado el switch, actualizamos la lista
            });
        });

        function getFechaHoraActual() {
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
}
  