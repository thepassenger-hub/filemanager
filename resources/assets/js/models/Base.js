class Base {
    constructor(name, path, type) {
        this.name = name
        this.path = path
        this.type = type
    }

    create(){
        return new Promise((resolve, reject) => {
            axios.put('/api/files', {
                    path: this.path,
                    file: this.name,
                    type: this.type
                })
                .then(response => resolve(response))
                .catch(error => reject(error.response.data))
        })
    }

    rename(newName){
        return new Promise((resolve, reject) => {
            axios.patch('/api/files/rename', {
                    file: this.path,
                    newName: newName
                })
                .then(response => resolve(response.data))
                .catch(error => reject(error.response.data));
        })
    }

    chmod(value){

        return new Promise((resolve, reject) => {
            axios.patch('/api/files/permission', {
                     file: this.path,
                     permission: value
                 })
                 .then(response => resolve(response.data))
                 .catch(error => reject(error.response.data));

        })
    }

    copy(path) {

        return new Promise((resolve, reject) => {
            axios.put('/api/files/copy', {
                    path: path,
                    file: this.path,
                    type: this.type
                })
                .then(response => resolve(response.data))
                .catch(error => reject(error.response.data));
        })
    }

    move(path) {
        return new Promise((resolve, reject) => {
            axios.patch('/api/files/move', {
                    path: path,
                    file: this.path
                })
                .then(response => resolve(response.data))
                .catch(error => reject(error.response.data));
        })
    }

    delete() {
        return new Promise((resolve, reject) => {
            axios.delete('/api/files', {
                    params: {
                        type: this.type,
                        path: this.path
                    }
                })
                .then(response => resolve(response.data))
                .catch(error => reject(error.response.data));
        });
    }


}

export default Base;