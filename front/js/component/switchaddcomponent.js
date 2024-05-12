class SwitchAddComponent extends Fronty.ModelComponent {
    constructor(switchsModel, userModel, router) {
      super(Handlebars.templates.switchadd, switchsModel);
      this.switchsModel = switchsModel; // posts
      
      this.userModel = userModel; // global
      this.addModel('user', userModel);
      this.router = router;
  
      this.switchsService = new SwitchsService();
  
      this.addEventListener('click', '#savebutton', () => {
        var newSwitch = {};
        newSwitch.name = $('#name').val();
        newSwitch.description = $('#description').val();
        newSwitch.auto_off_time = $('#auto_off_time').val();
        newSwitch.owner = this.userModel.currentUser;
        newSwitch.public_id = this.generateUUID();
        newSwitch.private_id = this.generateUUID();

        this.switchsService.addSwitch(newSwitch)
          .then(() => {
            this.router.goToPage('switchs');
          })
          .fail((xhr, errorThrown, statusText) => {
            if (xhr.status == 400) {
              this.switchsModel.set(() => {
                this.switchsModel.errors = xhr.responseJSON;
              });
            } else {
              alert('an error has occurred during request: ' + statusText + '.' + xhr.responseText);
            }
          });
      });
    }
    
    onStart() {
      this.switchsModel.setSelectedSwitch(new SwitchModel());
    }

    generateUUID() {
        // Genera un UUID versión 4 (aleatorio)
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random() * 16 | 0,
                v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }
  }
  