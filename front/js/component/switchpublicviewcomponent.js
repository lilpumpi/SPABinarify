class SwitchPublicViewComponent extends Fronty.ModelComponent {
    constructor(switchsModel, userModel, router) {
      super(Handlebars.templates.switchpublicview, switchsModel);
  
      this.switchsModel = switchsModel; // switches
      this.userModel = userModel; // global
      this.addModel('user', userModel);
      this.router = router;
  
      this.switchsService = new SwitchsService();
      this.suscriptionServices = new SuscriptionsService();
        
      // Event listener para encender el switch
      this.addEventListener('click', '#btn-view-suscribe', (event) => {
        this.subscribeSwitch(event);
      });
  
      // Event listener para apagar el switch
      this.addEventListener('click', '#btn-view-unsuscribe', (event) => {
        this.unsubscribeSwitch(event);
      });
  
      //Falta boton suscribirse y desuscribirse
      
    }
  
    onStart() {
      var switchId = this.router.getRouteQueryParam('public_id');
      this.loadSwitch(switchId);
    }
  
    loadSwitch(switchId) {
        if (switchId != null) {
          this.switchsService.findSwitch(switchId)
            .then((switchitem) => {
              console.log(switchitem);
              console.log(switchitem.id);
              // Verificar si el usuario está suscrito a este switch
              this.checkIfSubscribed(switchitem.id)
                .then((subscribed) => {
                    console.log("suscrito: " + subscribed);
                  // Asignar el estado de suscripción al switchitem
                  switchitem.subscribed = subscribed;
                  // Establecer el switchitem en el modelo seleccionado
                  this.switchsModel.setSelectedSwitch(switchitem);
                  console.log(this.switchsModel.selectedSwitch);
                });
            })
            .catch((error) => {
              console.error(error);
              alert('Error al cargar el switch');
            });
        }
    }
      
  
    // Event listener para desuscribirse del switch
    unsubscribeSwitch(event) {
        // La petición REST necesita el switch
        var publicId = event.target.getAttribute('item');
        
        this.switchsService.findSwitch(publicId)
        .then((switchitem) => {
            // Buscamos la suscripción a partir de un switch
            return this.suscriptionServices.findSuscription(switchitem.id);
        })
        .then((suscription) => {
            if (suscription && suscription.id) {
            // Enviamos petición REST para borrar la suscripción
            return this.suscriptionServices.deleteSuscription(suscription.id);
            } else {
            throw new Error('Suscription not found');
            }
        })
        .then(() => {
            this.loadSwitch(publicId);
        })
        .catch((error) => {
            console.error(error);
            alert('Suscription cannot be deleted or another error occurred');
        });
    }
  
    // Event listener para suscribirse al switch
    subscribeSwitch(event) {
        // La petición REST necesita el switch
        var publicId = event.target.getAttribute('item');
        
        this.switchsService.findSwitch(publicId)
        .then((switchitem) => {
    
            // Creamos una nueva suscripción basada en el switch encontrado
            var newSuscription = {
                id: switchitem.id
            // Agrega otros campos necesarios para la suscripción aquí
            };
    
            // Enviamos la petición REST para añadir la nueva suscripción
            return this.suscriptionServices.addSuscription(newSuscription);
        })
        .then(() => {
            this.loadSwitch(publicId);
        })
        .catch((error) => {
            console.error(error);
            alert('Suscription cannot be created or another error occurred');
        });
    }

    //Comprueba si existe una suscripcion del usuario y el switch
    checkIfSubscribed(switchId) {
        return this.suscriptionServices.findSuscription(switchId)
          .then((response) => {
            return true;
          })
          .catch(() => {
            return false; // Devolver false en caso de error
          });
    }
  
   
  }
  