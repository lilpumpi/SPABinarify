class SwitchsModel extends Fronty.Model {

    constructor() {
      super('SwitchsModel'); //call super
  
      // model attributes
      this.switchs = [];
    }
  
    setSelectedSwitch(switchdevice) {
      this.set((self) => {
        self.selectedSwitch = switchdevice;
      });
    }
  
    setSwitchs(switchs) {
      this.set((self) => {
        self.switchs = switchs;
      });
    }
  }
  