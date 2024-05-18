class DashboardModel extends Fronty.Model {

    constructor() {
      super('DashboardModel'); //call super
  
      // model attributes
      this.switches = [];
      this.suscriptions = [];
    }
  
    setSelectedSuscription(suscription) {
        this.set((self) => {
          self.selectedSuscription = suscription;
        });
    }
    
    setSuscriptions(suscriptions) {
        this.set((self) => {
            self.suscriptions = suscriptions;
        });
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
  