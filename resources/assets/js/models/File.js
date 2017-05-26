import Base from './Base.js';

class File extends Base{
    
    constructor(item){
        super();
        this.path = item.path;
        this.size = item.size;
        this.lastModified = new Date(item.lastModified *1000);
        this.type = 'file';
    }

    open(){
        return new Promise((resolve, reject) => {
            axios.get('/api/files/open', {
                    params: {
                        file: this.path
                    }
                })
                .then(response => resolve(response.data))
                .catch(error => reject(error.response.data));
        })
    }

    download() {
        return new Promise((resolve, reject) => {
            axios.get('/api/files/download', {
                params: {
                    file: this.path
                }
            })
            .then(response => resolve(response.data))
            .catch(error => reject(error.response.data));
        })
    }
}

export default File;