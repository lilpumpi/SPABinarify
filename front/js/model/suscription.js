class SuscriptionModel extends Fronty.Model {
    
    constructor(id, switch_id, nombre, descripcion, owner, auto_off_time, last_time, status, user) {
      super('SwitchModel'); //call super
      
      if (id) {
        this.id = id;
      }
      
      //Datos del switch de la suscripcion
      if (switch_id) {
        this.switch_id = switch_id;
      }
      
      if (nombre) {
        this.nombre = nombre;
      }

      if (descripcion) {
        this.descripcion = descripcion;
      }

      if (owner) {
        this.owner = owner;
      }

      if (auto_off_time) {
        this.auto_off_time = auto_off_time;
      }

      if (last_time) {
        this.last_time = last_time;
      }

      if (status) {
        this.status = status;
      }


      //Datos del usuario de la suscripcion
      if (user) {
        this.user = user;
      }
    }
  
    setSwitch(switch_id) {
      this.set((self) => {
        self.switch_id = switch_id;
      });
    }

    setNombre(nombre) {
      this.set((self) => {
        self.nombre = nombre;
      });
    }

    setDescripcion(descripcion) {
      this.set((self) => {
        self.descripcion = descripcion;
      });
    }

    setAutoOffTime(auto_off_time) {
      this.set((self) => {
        self.auto_off_time = auto_off_time;
      });
    }

    setLastTime(lastTime) {
      this.set((self) => {
        self.lastTime = lastTime;
      });
    }

    setOwner(owner) {
      this.set((self) => {
        self.owner = owner;
      });
    }

    setStatus(status) {
      this.set((self) => {
        self.status = status;
      });
    }

    setUsername(user) {
        this.set((self) => {
          self.user = user;
        });
    }

  }
  