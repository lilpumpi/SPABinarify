class SwitchViewComponent extends Fronty.ModelComponent {
  constructor(switchsModel, userModel, router) {
    super(Handlebars.templates.switchview, switchsModel);

    this.switchsModel = switchsModel; // switches
    this.userModel = userModel; // global
    this.addModel('user', userModel);
    this.router = router;

    this.switchsService = new SwitchsService();
      
    // Event listener para encender el switch
    this.addEventListener('click', '#btn-encender', (event) => {
      this.encenderSwitch(event);
    });

    // Event listener para apagar el switch
    this.addEventListener('click', '#btn-apagar', (event) => {
      this.apagarSwitch(event);
    });

    //Falta boton suscribirse y desuscribirse
    
  }

  onStart() {
    var switchId = this.router.getRouteQueryParam('private_id');
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

  // Event listener para encender el switch
  encenderSwitch(event) {
    //La petición REST para actualizar un switch solo necesita el tiempo y la fecha
    var newSwitch = {};
    newSwitch.private_id = event.target.getAttribute('item');
    newSwitch.auto_off_time = 10;
    newSwitch.last_time = this.getFechaHoraActual();

     //Enviamos petición REST a update a través de SwitchService
    this.switchsService.updateSwitch(newSwitch)
    .fail(() => {
        alert('switch cannot be turned on');
    })
    .always(() => {
      this.loadSwitch(newSwitch.private_id);
    });
  }

  // Event listener para apagar el switch
  apagarSwitch(event) {
      //La petición REST para actualizar un switch solo necesita el tiempo y la fecha
      var newSwitch = {};
      newSwitch.private_id = event.target.getAttribute('item');
      newSwitch.auto_off_time = 0;

      //Enviamos petición REST a update a través de SwitchService
      this.switchsService.updateSwitch(newSwitch)
      .fail(() => {
          alert('switch cannot be turned off');
      })
      .always(() => {
        this.loadSwitch(newSwitch.private_id);
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
