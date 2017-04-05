import Base from './Base.js';

class Dir extends Base {

    constructor(item){
        super();
        this.path = item.path;
        this.lastModified = new Date(item.lastModified *1000);
        this.type = 'folder';
    }
}

export default Dir;