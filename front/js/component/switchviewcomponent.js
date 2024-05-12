class SwitchViewComponent extends Fronty.ModelComponent {
    constructor(switchsModel, userModel, router) {
      super(Handlebars.templates.switchview, switchsModel);
  
      this.switchsModel = switchsModel; // switches
      this.userModel = userModel; // global
      this.addModel('user', userModel);
      this.router = router;
  
      this.switchsService = new SwitchsService();
        
      //Boton suscribirse/Desuscribirse
      
    }
  
    onStart() {
      var switchId = this.router.getRouteQueryParam('id');
      this.loadSwitch(switchId);
    }
  
    loadSwitch(switchId) {
      if (switchId != null) {
        this.switchsService.findSwitch(switchId)
          .then((switchitem) => {
            this.switchsModel.setSelectedSwitch(switchitem);
          });
      }
    }
  }
  