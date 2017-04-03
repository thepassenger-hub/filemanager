import axios from 

class File {
    constructor(item){
        this.path = item.path;
        this.size = item.size;
        // this.created_at = item.created_at;
    }

    data() {
        return {
            path: this.path,
            size: this.size,
            created_at: this.created_at
        }
    }

    rename(newName){
        return new Promise((resolve, reject) => {
            axios.patch('/api/files/rename', {
                    file: this.path,
                    newName: newName
                })
                .then(response => resolve(response.data))
                .catch(error => reject(error.response));
        })
    }
}

export default File;