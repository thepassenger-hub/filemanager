import Base from './Base.js';
class File extends Base{
    constructor(item){
        super();
        this.path = item.path;
        this.size = item.size;
        this.lastModified = new Date(item.lastModified *1000);
        this.type = 'file';
        // this.created_at = item.created_at;
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
}

export default File;