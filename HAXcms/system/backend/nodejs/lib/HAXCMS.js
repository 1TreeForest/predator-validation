const fs = require('fs-extra');
const path = require('path');
const crypto = require('crypto');
const url = require('url');
const JWT = require('jsonwebtoken');
const utf8 = require('utf8');
const { Git } = require('git-interface');
const substr = require('locutus/php/strings/substr')

class GitPlus extends Git {
  async revert(count) {
    let counter = 0;
    // sanity check
    if (count < 1) {
        count = 1;
    }
    while (counter != count) {
        await this.gitExec("reset --hard HEAD~1");
        counter++;
    }
    return true;
  }
}
const JSONOutlineSchema = require('./JSONOutlineSchema.js');
const JSONOutlineSchemaItem = require('./JSONOutlineSchemaItem.js');
const FeedMe = require('./RSS.js');
const sharp = require('sharp');
const Twig = require('twig');
const filter_var = require('./filter_var.js');
const array_search = require('locutus/php/array/array_search');
const array_unshift = require('locutus/php/array/array_unshift');
const implode = require('locutus/php/strings/implode');
const explode = require('locutus/php/strings/explode');
const array_unique = require("locutus/php/array/array_unique");
const base64_encode = require('locutus/php/url/base64_encode');
const json_encode = require('locutus/php/json/json_encode');
const strtr = require('locutus/php/strings/strtr');
const usort = require('locutus/php/array/usort');
const HAXCMS_ROOT = process.env.HAXCMS_ROOT || __dirname + "/../../../../";
const HAXCMS_DEFAULT_THEME = 'clean-one';
const HAXCMS_FALLBACK_HEX = '#3f51b5';
// HAXcms core
const HAXCMS = new class HAXCMSClass {
  constructor() {
    this.HAXCMS_ROOT = HAXCMS_ROOT;
    this.HAXCMS_DEFAULT_THEME = HAXCMS_DEFAULT_THEME;
    this.HAXCMS_FALLBACK_HEX = HAXCMS_FALLBACK_HEX;
    this.configDirectory = this.HAXCMS_ROOT + '_config/';
    this.apiBase = 'system/api/';
    this.coreConfigPath = this.HAXCMS_ROOT + 'system/coreConfig/';
    this.sitesDirectory = 'sites';
    this.archivedDirectory = 'archived';
    this.publishedDirectory = 'published';
    this.basePath = '/';
    this.sessionJwt = null;
    this.protocol = url.protocol;
    this.domain = url.hostname;
    // @todo these need to be read in from a file
    this.privateKey = 'NEEDTOGETTHIS'; // @todo need to set this
    this.superUser = {
      name: 'admin',
      password: 'admin',
    };
    this.user = {
      name: 'admin',
      password: 'admin',
    };
    // makes it easier to request a new item from the schema factory
    this.outlineSchema = new JSONOutlineSchema();
    this.config = JSON.parse(fs.readFileSync(path.join(this.configDirectory, "config.json"),
    {encoding:'utf8', flag:'r'}, 'utf8'));
    this.userData = JSON.parse(fs.readFileSync(path.join(this.configDirectory, "userData.json"),
    {encoding:'utf8', flag:'r'}, 'utf8'));
    this.salt = fs.readFileSync(path.join(this.configDirectory, "SALT.txt"),
    {encoding:'utf8', flag:'r'}, 'utf8');
  }
  /**
   * Load a site off the file system with option to create
   */
  async loadSite(name, create = false, domain = null)
    {
        let tmpname = decodeURIComponent(name);
        tmpname = this.cleanTitle(tmpname, false);
        // check if this exists, load but fallback for creating on the fly
        if (fs.existsSync(this.HAXCMS_ROOT + '/' + this.sitesDirectory + '/' + tmpname) && 
          fs.lstatSync(this.HAXCMS_ROOT + '/' + this.sitesDirectory + '/' + tmpname).isDirectory() && !create
        ) {
            let site = new HAXCMSSite();
            await site.load(this.HAXCMS_ROOT + '/' + this.sitesDirectory,
                this.basePath + this.sitesDirectory + '/',
                tmpname);
            let siteDirectoryPath = site.directory + '/' + site.name;
            // sanity checks to ensure we'll actually deliver a site
            if (!fs.lstatSync(siteDirectoryPath + '/build').isSymbolicLink()) {
              if (fs.lstatSync(siteDirectoryPath + '/build').isDirectory()
              ) {
                fs.unlink(siteDirectoryPath + '/build');
              }
              fs.symlink('../../wc-registry.json', siteDirectoryPath + '/wc-registry.json');
              fs.symlink('../../build', siteDirectoryPath + '/build');
              if (!fs.lstatSync(siteDirectoryPath + '/dist').isSymbolicLink()) {
                fs.symlink('../../dist', siteDirectoryPath + '/dist');
              }
              if (!fs.lstatSync(siteDirectoryPath + '/node_modules').isSymbolicLink()) {
                fs.symlink(
                    '../../node_modules',
                    siteDirectoryPath + '/node_modules'
                );
              }
            }
            return site;
        }
        else if (create) {
            // attempt to create site
            return await this.createSite(name, domain);
        }
        return false;
  }
  /**
     * Attempt to create a new site on the file system
     *
     * @var $name name of the new site to create
     * @var $domain optional domain name to utilize during setup
     * @var $git git object details
     *
     * @return boolean true for success, false for failed
     */
   async createSite(name, domain = null, git = null)
   {
       // try and make the folder
       var site = new HAXCMSSite();
       // see if we can get a remote setup on the fly
       if (git && !git.url && this.config.site.git) {
           git = this.config.site.git;
           // getting really into fallback mode here
           if (git['url']) {
               git['url'] += '/' + name + '.git';
           }
       }

       if (
           await site.newSite(
               HAXCMS_ROOT + this.sitesDirectory,
               this.basePath + this.sitesDirectory + '/',
               name,
               git,
               domain
           )
       ) {
           return site;
       }
       return false;
   }
  /**
   * @todo Need to support CLI
   */
  isCLI() {
    return false;
  }
  /**
   * Load theme location data as mix of config and system
   */
  getThemes()
  {
      return this.config.themes;
  }
  /**
   * Generate machine name
   */
  generateMachineName(name) {
      return name.replace([
      '/[^a-zA-Z0-9]+/',
      '/-+/',
      '/^-+/',
      '/-+$/',
      ], ['-', '-', '', '']).toLowerCase();
  }
  /**
   * Clean up a title / sanitize the input string for file system usage
   */
  cleanTitle(value, stripPage = true)
  {
      let cleanTitle = value.trim();
      // strips off the identifies for a page on the file system
      if (stripPage) {
          cleanTitle = cleanTitle.replace(
              'pages/',
              '')
              .replace('/index.html', '');
      }
      cleanTitle = cleanTitle.replace(' ', '-').toLowerCase();
      cleanTitle = cleanTitle.replace('/[^\w\-\/\s]+/u', '-');
      cleanTitle = cleanTitle.replace('/--+/u', '-');
      // ensure we don't return an empty title or it could break downstream things
      if (cleanTitle == '') {
          cleanTitle = 'blank';
      }
      return cleanTitle;
  }
  /**
   * Validate that a request token is accurate
   */
  validateRequestToken(token = null, value = '', query)
    {
        if (this.isCLI()) {
            return true;
        }
        // default token is POST
        if (token == null && query['token']) {
          token = query['token'];
        }
        if (token != null) {
          if (token == this.getRequestToken(value)) {
            return true;
          }
        }
        return true;
    }
    getRequestToken(value = '')
    {
        return this.hmacBase64(value, this.privateKey + this.salt);
    }
    hmacBase64(data, key)
    {
      var buf1 = crypto.createHmac("sha256", "0").update(data).digest();
      var buf2 = Buffer.from(key);
      // generate the hash
      return Buffer.concat([buf1, buf2]).toString('base64');
    }
    /**
     * Generate a valid HAX App store specification schema for connecting to this site via JSON.
     */
    siteConnectionJSON()
    {
        return {
      "details": {
        "title": "Local files",
        "icon": "perm-media",
        "color": "light-blue",
        "author": "HAXCMS",
        "description": "HAXCMS integration for HAX",
        "tags": ["media", "hax"]
      },
      "connection": {
        "protocol": this.protocol,
        "url": this.domain + this.basePath,
        "operations": {
          "browse": {
            "method": "GET",
            "endPoint": "system/api/listFiles",
            "pagination": {
              "style": "link",
              "props": {
                "first": "page.first",
                "next": "page.next",
                "previous": "page.previous",
                "last": "page.last"
              }
            },
            "search": {
            },
            "data": {
            },
            "resultMap": {
              "defaultGizmoType": "image",
              "items": "list",
              "preview": {
                "title": "name",
                "details": "mime",
                "image": "url",
                "id": "uuid"
              },
              "gizmo": {
                "source": "url",
                "id": "uuid",
                "title": "name",
                "type": "type"
              }
            }
          },
          "add": {
            "method": "POST",
            "endPoint": "system/api/saveFile",
            "acceptsGizmoTypes": [
              "image",
              "video",
              "audio",
              "pdf",
              "svg",
              "document",
              "csv",
              "archive",
              "markdown",
              "*",
              "html"
            ],
            "resultMap": {
              "item": "data.file",
              "defaultGizmoType": "image",
              "gizmo": {
                "source": "url",
                "id": "uuid"
              }
            }
          }
        }
      }
    };
    }
    /**
     * Validate a JTW during POST
     */
    validateJWT(req, res)
    {
      if (this.isCLI()) {
        return true;
      }
      var request = false;
      if (this.sessionJwt && this.sessionJwt != null) {
        request = this.decodeJWT(this.sessionJwt);
      }
      if (request == false && req.body && req.body['jwt'] && req.body['jwt'] != null) {
        request = this.decodeJWT(req.body['jwt'])
      }
      if (request == false && res.query && res.query['jwt'] && res.query['jwt'] != null) {
        request = this.decodeJWT(res.query['jwt'])
      }
      // if we were able to find a valid JWT in that mess, try and validate it
      if (  
          request != false &&
          request.id &&
          request.id == this.getRequestToken('user') &&
          request.user &&
          this.validateUser(request.user)) {
        return true;
      }
      return false;
    }
    /**
     * Get user's JWT
     */
    getJWT(name = null)
    {
        let token = {};
        token['id'] = this.getRequestToken('user');
        let n = Math.floor(Date.now() / 1000);
        // used at time
        token['iat'] = n;
        // expiration time, 15 minutes
        token['exp'] = n + (15 * 60);
        // if the user was supplied then add to token, if not it's relatively worthless but oh well :)
        if (name) {
            token['user'] = name;
        }
        return JWT.sign(token, this.privateKey + this.salt);
    }
    /**
     * Decode the JWT to ensure accuracy, return false if an error happens
     */
    decodeJWT(key) {
      // if it can decode, it'll be an object, otherwise it's false
      try {
        return JWT.verify(key, this.privateKey + this.salt);
      }
      catch (e) {
        return false;
      }
    }
    /**
     * Get user's Refresh Token
     */
    getRefreshToken(name = null) {
      let token = {};
      token['user'] = name;
      let n = Math.floor(Date.now() / 1000);
      token['iat'] = n;
      token['exp'] = n + (24 * 60 * 60);
      return JWT.sign(token, this.refreshPrivateKey + this.salt);
    }
    /**
     * Decode the JWT to ensure accuracy, return false if an error happens
     */
    decodeRefreshToken(key) {
      // if it can decode, it'll be an object, otherwise it's false
      try {
        return JWT.verify(key, this.refreshPrivateKey + this.salt);
      }
      catch (e) {
        return false;
      }
    }
    /**
     * Validate a JTW during POST
     */
    validateRefreshToken(endOnInvalid = true, req) {
      if (this.isCLI()) {
        return true;
      }
      // get the refresh token from cookie
      let refreshToken = req.cookies['haxcms_refresh_token'];
      // if there isn't one then we have to bail hard
      if (!refreshToken) {
       res.send(401);
      }
      // if there is a refresh token then decode it
      let refreshTokenDecoded = this.decodeRefreshToken(refreshToken);
      let n = Math.floor(Date.now() / 1000);
      // validate the token
      // make sure token has issued and expiration dates
      if ((refreshTokenDecoded.iat) && (refreshTokenDecoded.exp)) {
        // issued at date is less than or equal to now
        if (refreshTokenDecoded.iat <= n) {
          // expiration date is greater than now
          if (n < refreshTokenDecoded.exp) {
            // it's valid
            return refreshTokenDecoded;
          }
        }
      }
      // kick back the end if its invalid
      if (endOnInvalid) {
        res.cookie('haxcms_refresh_token', '', { 
          expires:1,     
        });
        res.send(401);
      }
      return false;
    }
    /**
     * Validate that a user name that came across in a JWT decode is legit
     */
    validateUser(name)
    {
        if (
            this.user.name === name
        ) {
            return true;
        }
        else if (
            this.superUser.name === name
        ) {
            return true;
        }
        else {
            usr = {};
            usr.name = name;
            usr.grantAccess = false;
            // fire custom event for things to respond to as needed
            // this is for SaaS providers to provide global validation
            return usr.grantAccess;
        }
        return false;
    }
    /**
     * test the active user login based on session.
     */
    testLogin(name, pass, adminFallback = false)
    {
        if (
            this.user.name === name &&
            this.user.password === pass
        ) {
            return true;
        }
        // if fallback is allowed, meaning the super admin then let them in
        // the default is to strictly test for the login in question
        // the fallback being allowable is useful for managed environments
        else if (
            adminFallback &&
            this.superUser.name === name &&
            this.superUser.password === pass
        ) {
            return true;
        }
        else {
            let usr = {};
            usr.name = name;
            usr.password = pass;
            usr.adminFallback = adminFallback;
            usr.grantAccess = false;
            // fire custom event for things to respond to as needed
            return usr.grantAccess;
        }
        return false;
    }
}

// a site object
class HAXCMSSite
{
    constructor() {
        this.name;
        this.manifest;
        this.directory;
        this.basePath = '/';
        this.language = 'en-us';
    }
      /**
   * Clean up a title / sanitize the input string for file system usage
   */
  cleanTitle(value, stripPage = true)
  {
      let cleanTitle = value.trim();
      // strips off the identifies for a page on the file system
      if (stripPage) {
          cleanTitle = cleanTitle.replace(
              'pages/',
              '')
              .replace('/index.html', '');
      }
      cleanTitle = cleanTitle.replace(' ', '-').toLowerCase();
      cleanTitle = cleanTitle.replace('/[^\w\-\/\s]+/u', '-');
      cleanTitle = cleanTitle.replace('/--+/u', '-');
      // ensure we don't return an empty title or it could break downstream things
      if (cleanTitle == '') {
          cleanTitle = 'blank';
      }
      return cleanTitle;
  }
    /**
     * Load a site based on directory and name
     */
    async load(directory, siteBasePath, name)
    {
        this.name = name;
        let tmpname = decodeURIComponent(name);
        tmpname = this.cleanTitle(tmpname, false);
        this.basePath = siteBasePath;
        this.directory = directory;
        this.manifest = new JSONOutlineSchema();
        await this.manifest.load(this.directory + '/' + tmpname + '/site.json');
    }
    /**
     * Initialize a new site with a single page to start the outline
     * @var directory string file system path
     * @var siteBasePath string web based url / base_path
     * @var name string name of the site
     * @var gitDetails git details
     * @var domain domain information
     *
     * @return HAXCMSSite object
     */
    async newSite(
        directory,
        siteBasePath,
        name,
        gitDetails,
        domain = null
    ) {
        // calls must set basePath internally to avoid page association issues
        this.basePath = siteBasePath;
        this.directory = directory;
        this.name = name;
        // clean up name so it can be in a URL / published
        let tmpname = decodeURIComponent(name);
        tmpname = this.cleanTitle(tmpname, false);
        let loop = 0;
        let newName = tmpname;
        if (fs.pathExistsSync(directory + "/" + newName)) {
          while (fs.lstatSync(directory  + "/" + newName).isFile()) {
              loop++;
              newName = tmpname + '-' + loop;
          }
        }
        tmpname = newName;
        // attempt to shift it on the file system
        await this.recurseCopy(
            HAXCMS.HAXCMS_ROOT + '/system/boilerplate/site',
            directory + '/' + tmpname
        );
        // create symlink to make it easier to resolve things to single built asset buckets
        await fs.symlink('../../wc-registry.json', directory + '/' + tmpname + '/wc-registry.json');
        await fs.symlink('../../build', directory + '/' + tmpname + '/build');
        // symlink to do local development if needed
        await fs.symlink('../../dist', directory + '/' + tmpname + '/dist');
        // symlink to do project development if needed
        if (fs.lstatSync(HAXCMS.HAXCMS_ROOT + '/node_modules').isSymbolicLink() || fs.lstatSync(HAXCMS.HAXCMS_ROOT + '/node_modules').isDirectory()) {
          await fs.symlink(
            '../../node_modules',
            directory + '/' + tmpname + '/node_modules'
            );
        }
        // links babel files so that unification is easier
        await fs.symlink(
            '../../../babel/babel-top.js',
            directory + '/' + tmpname + '/assets/babel-top.js'
        );
        await fs.symlink(
            '../../../babel/babel-bottom.js',
            directory + '/' + tmpname + '/assets/babel-bottom.js'
        );
        // default support is for gh-pages
        if (domain == null && (gitDetails != null && gitDetails.user)) {
            domain = 'https://' + gitDetails.user + '.github.io/' + tmpname;
        } else if (domain != null) {
            // put domain into CNAME not the github.io address if that exists
            await fs.writeFileSync(directory + '/' + tmpname + '/CNAME', domain);
        }
        // load what we just created
        this.manifest = new JSONOutlineSchema();
        // where to save it to
        this.manifest.file = directory + '/' + tmpname + '/site.json';
        // start updating the schema to match this new item we got
        this.manifest.title = name;
        this.manifest.location = this.basePath + tmpname + '/index.html';
        this.manifest.metadata = {};
        this.manifest.metadata.author = {};
        this.manifest.metadata.site = {};
        this.manifest.metadata.site.name = tmpname;
        this.manifest.metadata.site.domain = domain;
        this.manifest.metadata.site.created = Date.now();
        this.manifest.metadata.site.updated = Date.now();
        this.manifest.metadata.theme = {};
        this.manifest.metadata.theme.variables = {};
        this.manifest.metadata.node = {};
        this.manifest.metadata.node.fields = {};
        this.manifest.items = [];
        // create an initial page to make sense of what's there
        // this will double as saving our location and other updated data
        await this.addPage(null, 'Welcome to a new HAXcms site!', 'init');
        // put this in version control :) :) :)

        const git = new GitPlus({
          dir: directory + '/' + tmpname
        });
        // initalize git repo
        await git.init();
        try {
          await git.add();
          await git.commit('A new journey begins: ' + this.manifest.title + ' (' + this.manifest.id + ')');
          if (
              !(this.manifest.metadata.site && this.manifest.metadata.site.git && this.manifest.metadata.site.git.url) &&
              (gitDetails != null && gitDetails.url)
          ) {
              await this.gitSetRemote(gitDetails);
          }
          // write the managed files to ensure we get happy copies
          await this.rebuildManagedFiles();
          await this.gitCommit('Managed files updated');
        }
        catch(e){}
        return this;
    }
    /**
     * Return the forceUpgrade status which is whether to force end users to upgrade their browser
     * @return string status of forced upgrade, string as boolean since it'll get written into a JS file
     */
    getForceUpgrade() {
        if ((this.manifest.metadata.site.settings.forceUpgrade) && this.manifest.metadata.site.settings.forceUpgrade) {
            return "true";
        }
        return "false";
    }
    /**
     * Return the sw status
     * @return string status of forced upgrade, string as boolean since it'll get written into a JS file
     */
    getServiceWorkerStatus() {
        if ((this.manifest.metadata.site.settings.sw) && this.manifest.metadata.site.settings.sw) {
            return true;
        }
        return false;
    }
    /**
     * Return an array of files we care about rebuilding on managed file operations
     * @return array keyed array of files we wish to pull from the boilerplate and keep in sync
     */
    getManagedTemplateFiles() {
        return {
            'htaccess':'.htaccess', // not templated (yet) but ensures self refreshing if we tweak it
            '404':'404.html',
            'msbc':'browserconfig.xml',
            'build':'build.js',
            'buildhaxcms':'build-haxcms.js',
            'index':'index.html',
            'manifest':'manifest.json',
            'package':'package.json',
            'polymer':'polymer.json',
            'push':'push-manifest.json',
            'robots':'robots.txt',
            'sw':'service-worker.js',
            'outdated':'assets/upgrade-browser.html',
        };
    }
    /**
     * Reprocess the files that twig helps set in their static
     * form that the user is not in control of.
     */
    async rebuildManagedFiles() {
      let templates = this.getManagedTemplateFiles();
      // this can't be there by default since it's a dynamic file and we only
      // want to update this when we are refreshing the managed files directly
      templates['indexphp'] = 'index.php';
      let siteDirectoryPath = this.directory + '/' + this.manifest.metadata.site.name;
      boilerPath = HAXCMS.HAXCMS_ROOT + '/system/boilerplate/site/';
      for (var key in templates) {
        fs.copy(boilerPath + templates[key], siteDirectoryPath + '/' + templates[key]);
      }
      let licenseData = this.getLicenseData('all');
      let licenseLink = '';
      let licenseName = '';
      if ((this.manifest.license) && (licenseData[this.manifest.license])) {
        licenseLink = licenseData[this.manifest.license]['link'];
        licenseName = 'License: ' + licenseData[this.manifest.license]['name'];
      }
      
      let templateVars = {
          'hexCode': HAXCMS.HAXCMS_FALLBACK_HEX,
          'version': HAXCMS.getHAXCMSVersion(),
          'basePath' :
              this.basePath + this.manifest.metadata.site.name + '/',
          'title': this.manifest.title,
          'short': this.manifest.metadata.site.name,
          'description': this.manifest.description,
          'forceUpgrade': this.getForceUpgrade(),
          'swhash': {},
          'ghPagesURLParamCount': 0,
          'licenseLink': licenseLink,
          'licenseName': licenseName,
          'serviceWorkerScript': this.getServiceWorkerScript(this.basePath + this.manifest.metadata.site.name + '/'),
          'bodyAttrs': this.getSitePageAttributes(),
          'metadata': this.getSiteMetadata(),
          'logo512x512': this.getLogoSize('512','512'),
          'logo256x256': this.getLogoSize('256','256'),
          'logo192x192': this.getLogoSize('192','192'),
          'logo144x144': this.getLogoSize('144','144'),
          'logo96x96': this.getLogoSize('96','96'),
          'logo72x72': this.getLogoSize('72','72'),
          'logo48x48': this.getLogoSize('48','48'),
          'favicon': this.getLogoSize('32','32'),
      };
      swItems = this.manifest.items;
      // the core files you need in every SW manifest
      coreFiles = [
          'index.html',
          this.getLogoSize('512','512'),
          this.getLogoSize('256','256'),
          this.getLogoSize('192','192'),
          this.getLogoSize('144','144'),
          this.getLogoSize('96','96'),
          this.getLogoSize('72','72'),
          this.getLogoSize('48','48'),
          'manifest.json',
          'site.json',
          '404.html',
      ];
      // loop through files directory so we can cache those things too
      if (handle = fs.readdirSync(siteDirectoryPath + '/files')) {
          while (false !== (file = await fs.readFileSync(handle,
            {encoding:'utf8', flag:'r'}))) {
              if (
                  file != "." &&
                  file != ".." &&
                  file != '.gitkeep' &&
                  file != '.DS_Store'
              ) {
                  // ensure this is a file
                  if (
                    fs.lstatSync(siteDirectoryPath + '/files/' + file).isFile()
                  ) {
                      coreFiles.push('files/' + file);
                  } else {
                      // @todo maybe step into directories?
                  }
              }
          }
      }
      for (var key in coreFiles) {
          coreItem = {};
          coreItem.location = coreFiles[key];
          swItems.push(coreItem);
      }
      // generate a legit hash value that's the same for each file name + file size
      for (var key in swItems) {
          let item = swItems[key];
          if (
              item.location === '' ||
              item.location === templateVars['basePath']
          ) {
              filesize = filesize(
                  siteDirectoryPath + '/index.html'
              );
          } else if (
            fs.pathExistsSync(siteDirectoryPath + '/' + item.location) &&
            fs.lstatSync(siteDirectoryPath + '/' + item.location).isFile()
          ) {
              filesize = filesize(
                  siteDirectoryPath + '/' + item.location
              );
          } else {
              // ?? file referenced but doesn't exist
              filesize = 0;
          }
          if (filesize !== 0) {
            templateVars['swhash'].push([
                  item.location,
                  strtr(
                      base64_encode(
                          hash_hmac(
                              'md5',
                              item.location + filesize,
                              'haxcmsswhash',
                              true
                          )
                      ),
                      {
                          '+':'',
                          '/':'',
                          '=':'',
                          '-':''
                      }
                  )
            ]);
          }
      }
      if ((this.manifest.metadata.theme.variables.hexCode)) {
        templateVars['hexCode'] = this.manifest.metadata.theme.variables.hexCode;
      }
      // put the twig written output into the file
      // @todo figure out how to port Twig
      //let loader = new \Twig\Loader\FilesystemLoader(siteDirectoryPath);
      //let twig = new \Twig\Environment(loader);
      /*for (var key in templates) {
          if (fs.lstatSync(siteDirectoryPath + '/' + templates[key]).isFile()) {
            fs.writeFileSync(
                siteDirectoryPath + '/' + templates[key],
                twig.render(templates[key], templateVars)
            );
          }
      }*/
    }
    /**
     * Rename a page from one location to another
     * This ensures that folders are moved but not the final index.html involved
     * It also helps secure the sites by ensuring movement is only within
     * their folder tree
     */
    renamePageLocation(oldItem, newItem) {
        let siteDirectory = this.directory + '/' + this.manifest.metadata.site.name;
        oldItem = oldItem.replace('./', '').replace('../', '');
        newItem = newItem.replace('./', '').replace('../', '');
        // ensure the path to the new folder is valid
        if (fs.pathExistsSync(siteDirectory + '/' + oldItem) &&
          fs.lstatSync(siteDirectory + '/' + oldItem).isFile()) {
            fs.mirror(
                siteDirectory + '/' + oldItem.replace('/index.html', ''),
                siteDirectory + '/' + newItem.replace('/index.html', '')
            );
            fs.unlink(siteDirectory + '/' + oldItem);
        }
    }
    /**
     * Basic wrapper to commit current changes to version control of the site
     */
    async gitCommit(msg = 'Committed changes')
    {
        // commit, true flag will attempt to make this a git repo if it currently isn't
        const git = new GitPlus({
          dir: this.directory + '/' + this.manifest.metadata.site.name
        });
        try {
          await git.add();
          await git.commit(msg);
          // commit should execute the automatic push flag if it's on
          if ((this.manifest.metadata.site.git.autoPush) && this.manifest.metadata.site.git.autoPush && (this.manifest.metadata.site.git.branch)) {
            await git.checkout(this.manifest.metadata.site.git.branch);
            await git.push();
          }
        }
        catch(e){}
        return true;
    }
    /**
     * Basic wrapper to revert top commit of the site
     */
    async gitRevert(count = 1)
    {
      const git = new GitPlus({
        dir: this.directory + '/' + this.manifest.metadata.site.name
      });
      try {
        await git.revert(count);
      }
      catch(e){}
      return true;
    }
    /**
     * Basic wrapper to commit current changes to version control of the site
     */
    async gitPush()
    {
      const git = new GitPlus({
        dir: this.directory + '/' + this.manifest.metadata.site.name
      });
      try {
        await git.add();
        await git.commit("commit forced");
        await git.push();
      }
      catch(e){}
      return true;
    }

    /**
     * Basic wrapper to commit current changes to version control of the site
     *
     * @var git a stdClass containing repo details
     */
    async gitSetRemote(gitDetails)
    {
      const git = new GitPlus({
        dir: this.directory + '/' + this.manifest.metadata.site.name
      });
      try {
        await repo.setRemote("origin", gitDetails.url);
      }
      catch(e){}
      return true;
    }
    /**
     * Add a page to the site's file system and reflect it in the outine schema.
     *
     * @var parent JSONOutlineSchemaItem representing a parent to add this page under
     * @var title title of the new page to create
     * @var template string which boilerplate page template / directory to load
     *
     * @return page repesented as JSONOutlineSchemaItem
     */
    async addPage(parent = null, title = 'New page', template = "default")
    {
        // draft an outline schema item
        let page = new JSONOutlineSchemaItem();
        // set a crappy default title
        page.title = title;
        if (parent == null) {
            page.parent = null;
            page.indent = 0;
        } else {
            // set to the parent id
            page.parent = parent.id;
            // move it one indentation below the parent; this can be changed later if desired
            page.indent = parent.indent + 1;
        }
        // set order to the page's count for default add to end ordering
        page.order = this.manifest.items.length;
        // location is the html file we just copied and renamed
        page.location = 'pages/welcome/index.html';
        page.metadata = {
          created: Date.now(),
          updated: Date.now(),
        }
        var location =
            this.directory +
            '/' +
            this.manifest.metadata.site.name +
            '/pages/welcome';
        // copy the page we use for simplicity (or later complexity if we want)
        switch (template) {
            case 'init':
                await this.recurseCopy(HAXCMS.HAXCMS_ROOT + '/system/boilerplate/page/init', location);
            break;
            default:
                await this.recurseCopy(HAXCMS.HAXCMS_ROOT + '/system/boilerplate/page/default', location);
            break;
        }
        this.manifest.addItem(page);
        await this.manifest.save();
        await this.updateAlternateFormats();
        return page;
    }
    /**
     * Save the site, though this basically is just a mapping to the manifest site.json saving
     */
    async save() {
      await this.manifest.save();
    }
    /**
     * Update RSS, Atom feeds, site map, legacy outline, search index
     * which are physical files and need rebuilt on chnages to data structure
     */
    async updateAlternateFormats(format = null)
    {
        let siteDirectory = this.directory + '/' + this.manifest.metadata.site.name + '/';
        if (format == null || format == 'rss') {
            // rip changes to feed urls
            let rss = new FeedMe();
            siteDirectory =
                this.directory + '/' + this.manifest.metadata.site.name + '/';
            fs.writeFileSync(siteDirectory + 'rss.xml', rss.getRSSFeed(this));
            fs.writeFileSync(
                siteDirectory + 'atom.xml',
                rss.getAtomFeed(this)
            );
        }
        // build a sitemap if we have a domain, kinda required...
       /* if (format == null || format == 'sitemap') {
                          // @todo sitemap generator needs an equivalent
          
          if ((this.manifest.metadata.site.domain)) {
                let domain = this.manifest.metadata.site.domain;
                //generator = new \Icamys\SitemapGenerator\SitemapGenerator(
                //    domain,
                //    siteDirectory
                //);
                let generator = {};
                // will create also compressed (gzipped) sitemap
                generator.createGZipFile = true;
                // determine how many urls should be put into one file
                // according to standard protocol 50000 is maximum value (see http://www.sitemaps.org/protocol.html)
                generator.maxURLsPerSitemap = 50000;
                // sitemap file name
                generator.sitemapFileName = "sitemap.xml";
                // sitemap index file name
                generator.sitemapIndexFileName = "sitemap-index.xml";
                // adding url `loc`, `lastmodified`, `changefreq`, `priority`
                for (var key in this.manifest.items) {
                    let item = this.manifest.items[key];
                    if (item.parent == null) {
                        priority = '1.0';
                    } else if (item.indent == 2) {
                        priority = '0.7';
                    } else {
                        priority = '0.5';
                    }
                    let updatedTime = Date.now();
                    updatedTime.setTimestamp(item.metadata.updated);
                    let d = new Date();
                    updatedTime.format(d.toISOString());
                    generator.addUrl(
                        domain + '/' + item.location.replace('pages/', '').replace('/index.html', ''),
                        updatedTime,
                        'daily',
                        priority
                    );
                }
                // generating internally a sitemap
                 generator.createSitemap();
                // writing early generated sitemap to file
                 generator.writeSitemap();
            }
        }*/
        if (format == null || format == 'legacy') {
            // now generate a static list of links. This is so we can have legacy fail-back iframe mode in tact
            fs.writeFileSync(
                siteDirectory + 'legacy-outline.html',
                `<!DOCTYPE html>
                <html lang="en">
                    <head>
                        <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
                        <meta content="utf-8" http-equiv="encoding">
                        <link rel="stylesheet" type="text/css"href="assets/legacy-outline.css">
                    </head>
                    <body>${this.treeToNodes(this.manifest.items)}</body>
                </html>`
            );
        }
        if (format == null || format == 'search') {
            // now generate the search index
            await fs.writeFileSync(
                siteDirectory + 'lunrSearchIndex.json',
                    json_encode(await this.lunrSearchIndex(this.manifest.items))
            );
        }
    }
    /**
     * Create Lunr.js style search index
     */
    async lunrSearchIndex(items) {
      let data = [];
      let textData;
      for (var key in items) {
        let item = items[key];
        let created = Date.now();
        if ((item.metadata) && (item.metadata.created)) {
          created = item.metadata.created;
        }
        textData = '';
        try {
          textData = await fs.readFileSync(this.directory + '/' + this.manifest.metadata.site.name + '/' + item.location,
          {encoding:'utf8', flag:'r'});
          textData = this.cleanSearchData(textData);
          // may seem silly but IDs in lunr have a size limit for some reason in our context..
          data.push({
            "id":substr(item.id.replace('-', '').replace('item-', ''), 0, 29),
            "title":item.title,
            "created":created,
            "location":item.location.replace('pages/', '').replace('/index.html', ''),
            "description":item.description,
            "text":textData,
          });
        }
        catch(e) {
          // if that failed, not concerned as it's just an index
          console.log(e);
        }
      }
      return data;
    }
    /**
     * Clean up data from a file and make it easy for us to index on the front end
     */
    cleanSearchData(text) {
      if (text == '' || text == null || !text) {
        return '';
      }
      // clean up initial, small, trim, replace end lines, utf8 no tags
      text = utf8.encode(text.replace(/(<([^>]+)>)/ig,"").replace("\n", ' ').toLowerCase().trim());
      // all weird chars
      text = text.replace('/[^a-z0-9\']/', ' ');
      text = text.replace("'", '');
      // all words 1 to 4 letters long
      text = text.replace('~\b[a-z]{1,4}\b\s*~', '');
      // all excess white space
      text = text.replace('/\s+/', ' ');
      // crush string to array and back to make an unique index
      text = implode(' ', array_unique(explode(' ', text)));
      return text;
    }
    /**
     * Sort items by a certain key value. Must be in the included list for safety of the sort
     * @var string key - the key name to sort on, only some supported
     * @var string dir - direction to sort, ASC default or DESC to reverse
     * @return array items - sorted items based on the key used
     */
    sortItems(key, dir = 'ASC') {
        let items = this.manifest.items;
        switch (key) {
            case 'created':
            case 'updated':
            case 'readtime':
              this.__compareItemKey = key;
              this.__compareItemDir = dir;
              usort(items, function (a, b) {
                let key = this.__compareItemKey;
                let dir = this.__compareItemDir;
                if (a.metadata[key]) {
                  if (dir == 'DESC') {
                    return a.metadata[key] > b.metadata[key];
                  }
                  else {
                    return a.metadata[key] < b.metadata[key];
                  }
                }
              });
            break;
            case 'id':
            case 'title':
            case 'indent':
            case 'location':
            case 'order':
            case 'parent':
            case 'description':
                usort(items, function (a, b) {
                  if (dir == 'ASC') {
                    return a[key] > b[key];
                  }
                  else {
                    return a[key] < b[key];
                  }
                });
            break;
        }
        return items;
    }
    /**
     * Build a JOS into a tree of links recursively
     */
    treeToNodes(current, rendered = [], html = '')
    {
        let loc = '';
        for (var key in current) {
            let item = this.manifest.items[key];
            if (!array_search(item.id, rendered)) {
                loc +=`<li><a href="${item.location}" target="content">${item.title}</a>`;
                rendered.push(item.id);
                let children = [];
                for (var key2 in this.manifest.items) {
                    let child = this.manifest.items[key2];
                    if (child.parent == item.id) {
                        children.push(child);
                    }
                }
                // sort the kids
                usort(children, function (a, b) {
                    return a.order > b.order;
                });
                // only walk deeper if there were children for this page
                if (children.length > 0) {
                    loc += this.treeToNodes(children, rendered);
                }
                loc += '</li>';
            }
        }
        // make sure we aren't empty here before wrapping
        if (loc != '') {
            loc = '<ul>' + loc + '</ul>';
        }
        return html + loc;
    }
    /**
     * Load node by unique id
     */
    loadNode(uuid)
    {
      for (var key in this.manifest.items) {
        let item = this.manifest.items[key];
        if (item.id == uuid) {
            return item;
        }
      }
      return false;
    }
    /**
     * Get a social sharing image based on context of page or site having media
     * @var string page page to mine the image from or attempt to
     * @return string full URL to an image
     */
    getSocialShareImage(page = null) {
      // resolve a JOS Item vs null
      let id = null;
      if (page != null) {
        id = page.id;
      }
      let fileName = HAXCMS.staticCache(__FUNCTION__ + id);
      if (!(fileName)) {
        if (page == null) {
          page = this.loadNodeByLocation();
        }
        if ((page.metadata.files)) {
          for (var key in page.manifest.files) {
            let file = page.manifest.items[key];
            if (file.type == 'image/jpeg') {
              fileName = file.fullUrl;
            }
          }
        }
        // look for the theme banner
        if ((this.manifest.metadata.theme.variables.image)) {
          fileName = this.manifest.metadata.theme.variables.image;
        }
      }
      return fileName;
    }
    /**
     * Return attributes for the site
     * @todo make this mirror the drupal get attributes method
     * @return string eventually, array of data keyed by type of information
     */
    getSitePageAttributes() {
      return 'vocab="http://schema.org/" prefix="oer:http://oerschema.org cc:http://creativecommons.org/ns dc:http://purl.org/dc/terms/"';
    }
    /**
     * Return the base tag accurately which helps with the PWA / SW side of things
     * @return string HTML blob for hte <base> tag
     */
    getBaseTag() {
      return '<base href="' + this.basePath + this.name + '/" />';
    }
    /**
     * Return a standard service worker that takes into account
     * the context of the page it's been placed on.
     * @todo this will need additional vetting based on the context applied
     * @return string <script> tag that will be a rather standard service worker
     */
    getServiceWorkerScript(basePath = null, ignoreDevMode = false, addSW = true) {
      // because this can screw with caching, let's make sure we
      // can throttle it locally for developers as needed
      if (!addSW || (HAXCMS.developerMode && !ignoreDevMode)) {
        return "\n  <!-- Service worker disabled via settings -.\n";
      }
      // support dynamic calculation
      if (basePath == null) {
        basePath = this.basePath + this.name + '/';
      }
      return `
      <script>
        if ('serviceWorker' in navigator) {
          var sitePath = '${basePath}';
          // discover this path downstream of the root of the domain
          var swScope = window.location.pathname.substring(0, window.location.pathname.indexOf(sitePath)) + sitePath;
          if (swScope != document.head.getElementsByTagName('base')[0].href) {
            document.head.getElementsByTagName('base')[0].href = swScope;
          }
          window.addEventListener('load', function () {
            navigator.serviceWorker.register('service-worker.js', {
              scope: swScope
            }).then(function (registration) {
              registration.onupdatefound = function () {
                // The updatefound event implies that registration.installing is set; see
                // https://slightlyoff.github.io/ServiceWorker/spec/service_worker/index.html#service-worker-container-updatefound-event
                var installingWorker = registration.installing;
                installingWorker.onstatechange = function () {
                  switch (installingWorker.state) {
                    case 'installed':
                      if (!navigator.serviceWorker.controller) {
                        window.dispatchEvent(new CustomEvent('haxcms-toast-show', {
                          bubbles: true,
                          cancelable: false,
                          detail: {
                            text: 'Pages you view are cached for offline use.',
                            duration: 5000
                          }
                        }));
                      }
                    break;
                    case 'redundant':
                      throw Error('The installing service worker became redundant.');
                    break;
                  }
                };
              };
            }).catch(function (e) {
              console.warn('Service worker registration failed:', e);
            });
            // Check to see if the service worker controlling the page at initial load
            // has become redundant, since this implies there's a new service worker with fresh content.
            if (navigator.serviceWorker.controller) {
              navigator.serviceWorker.controller.onstatechange = function(event) {
                if (event.target.state === 'redundant') {
                  var b = document.createElement('paper-button');
                  b.appendChild(document.createTextNode('Reload'));
                  b.raised = true;
                  b.addEventListener('click', function(e){ window.location.reload(true); });
                  window.dispatchEvent(new CustomEvent('haxcms-toast-show', {
                    bubbles: true,
                    cancelable: false,
                    detail: {
                      text: 'A site update is available. Reload for latest content.',
                      duration: 8000,
                      slot: b,
                      clone: false
                    }
                  }));
                }
              };
            }
          });
        }
      </script>`;
    }
    /**
     * Load content of this page
     * @var JSONOutlineSchemaItem page - a loaded page object
     * @return string HTML / contents of the page object
     */
    async getPageContent(page) {
      if ((page.location) && page.location != '') {
        return filter_var(await fs.readFileSync(HAXCMS.HAXCMS_ROOT + '/' + HAXCMS.sitesDirectory + '/' + this.name + '/' + page.location,
        {encoding:'utf8', flag:'r'}));
      }
    }
    /**
     * Return accurate, rendered site metadata
     * @var JSONOutlineSchemaItem page - a loaded page object, most likely whats active
     * @return string an html chunk of tags for the head section
     * @todo move this to a render function / section / engine
     */
    getSiteMetadata(page = null, domain = null, cdn = '') {
      if (page == null) {
        page = new JSONOutlineSchemaItem();
      }
      // domain's need to inject their own full path for OG metadata (which is edge case)
      // most of the time this is the actual usecase so use the active path
      if (domain == null) {
        domain = HAXCMS.getURI();
      }
      // support preconnecting CDNs, sets us up for dynamic CDN switching too
      let preconnect = '';
      let base = './';
      if (cdn == '' && HAXCMS.cdn != './') {
        preconnect = `<link rel="preconnect" crossorigin href="${HAXCMS.cdn}">`;
        cdn = HAXCMS.cdn;
      }
      if (cdn != '') {
        // preconnect for faster DNS lookup
        preconnect = `<link rel="preconnect" crossorigin href="${cdn}">`;
        // preload rewrite correctly
        base = cdn;
      }
      let title = page.title;
      let siteTitle = this.manifest.title + ' | ' + page.title;
      let description = page.description;
      let hexCode = HAXCMS.HAXCMS_FALLBACK_HEX;
      if (description == '') {
        description = this.manifest.description;
      }
      if (title == '' || title == 'New item') {
        title = this.manifest.title;
        siteTitle = this.manifest.title;
      }
      if ((this.manifest.metadata.theme.variables.hexCode)) {
          hexCode = this.manifest.metadata.theme.variables.hexCode;
      }
      let metadata = `<meta charset="utf-8">
  ${preconnect}
  <link rel="preconnect" crossorigin href="https://fonts.googleapis.com">
  <link rel="preconnect" crossorigin href="https://cdnjs.cloudflare.com">
  <link rel="preconnect" crossorigin href="https://i.creativecommons.org">
  <link rel="preconnect" crossorigin href="https://licensebuttons.net">
  <link rel="preload" href="${base}build/es6/node_modules/mobx/dist/mobx.esm.js" as="script" crossorigin="anonymous" />
  <link rel="preload" href="${base}build/es6/node_modules/@lrnwebcomponents/haxcms-elements/lib/core/haxcms-site-builder.js" as="script" crossorigin="anonymous" />
  <link rel="preload" href="${base}build/es6/node_modules/@lrnwebcomponents/haxcms-elements/lib/core/haxcms-site-store.js" as="script" crossorigin="anonymous" />
  <link rel="preload" href="${base}build/es6/dist/my-custom-elements.js" as="script" crossorigin="anonymous" />
  <link rel="preload" href="${base}build/es6/node_modules/@lrnwebcomponents/haxcms-elements/lib/base.css" as="style" />
  <link rel="preload" href="./custom/build/custom.es6.js" as="script" crossorigin="anonymous" />
  <link rel="preload" href="./theme/theme.css" as="style" />  
  <meta name="generator" content="HAXcms">
  <link rel="manifest" href="manifest.json">
  <meta name="viewport" content="width=device-width, minimum-scale=1, initial-scale=1, user-scalable=yes">
  <title>${siteTitle}</title>
  <link rel="icon" href="${this.getLogoSize('16', '16')}">
  <meta name="theme-color" content="${hexCode}">
  <meta name="robots" content="index, follow">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="application-name" content="${title}">

  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="apple-mobile-web-app-title" content="${title}">

  <link rel="apple-touch-icon" sizes="48x48" href="${this.getLogoSize('48', '48')}">
  <link rel="apple-touch-icon" sizes="72x72" href="${this.getLogoSize('72', '72')}">
  <link rel="apple-touch-icon" sizes="96x96" href="${this.getLogoSize('96', '96')}">
  <link rel="apple-touch-icon" sizes="144x144" href="${this.getLogoSize('144', '144')}">
  <link rel="apple-touch-icon" sizes="192x192" href="${this.getLogoSize('192', '192')}">

  <meta name="msapplication-TileImage" content="${this.getLogoSize('144', '144')}">
  <meta name="msapplication-TileColor" content="${hexCode}">
  <meta name="msapplication-tap-highlight" content="no">
        
  <meta name="description" content="${description}" />
  <meta name="og:sitename" property="og:sitename" content="${this.manifest.title}" />
  <meta name="og:title" property="og:title" content="${title}" />
  <meta name="og:type" property="og:type" content="article" />
  <meta name="og:url" property="og:url" content="${domain}" />
  <meta name="og:description" property="og:description" content="${description}" />
  <meta name="og:image" property="og:image" content="${this.getSocialShareImage(page)}" />
  <meta name="twitter:card" property="twitter:card" content="summary_large_image" />
  <meta name="twitter:site" property="twitter:site" content="${domain}" />
  <meta name="twitter:title" property="twitter:title" content="${title}" />
  <meta name="twitter:description" property="twitter:description" content="${description}" />
  <meta name="twitter:image" property="twitter:image" content="${this.getSocialShareImage(page)}" />`;  
      // mix in license metadata if we have it
      let licenseData = this.getLicenseData('all');
      if ((this.manifest.license) && (licenseData[this.manifest.license])) {
          metadata += "\n" + '  <meta rel="cc:license" href="' + licenseData[this.manifest.license]['link'] + '" content="License: ' + licenseData[this.manifest.license]['name'] + '"/>' + "\n";
      }
      // add in twitter link if they provided one
      if ((this.manifest.metadata.author.socialLink) && strpos(this.manifest.metadata.author.socialLink, 'https://twitter.com/') === 0) {
          metadata += "\n" + '  <meta name="twitter:creator" content="' + this.manifest.metadata.author.socialLink.replace('https://twitter.com/', '@') + '" />';
      }
      HAXCMS.dispatchEvent('haxcms-site-metadata', metadata);
      return metadata;
    }
    /**
     * Load a node based on a path
     * @var path the path to try loading based on or search for the active from address
     * @return new JSONOutlineSchemaItem() a blank JOS item
     */
    loadNodeByLocation(path = null) {
        // load from the active address if we have one
        if (path == null) {
          path = path.resolve(__dirname).replace('/' + HAXCMS.sitesDirectory + '/' + this.name + '/', '');
        }
        path += "/index.html";
        // failsafe in case someone had closing /
        path = 'pages/' + path.replace('//', '/');
        for (var key in this.manifest.files) {
          let item = this.manifest.items[key];
          if (item.location == path) {
              return item;
          }
        }
       return new JSONOutlineSchemaItem();
    }
    /**
     * Generate or load the path to variations on the logo
     * @var string height height of the icon as a string
     * @var string width width of the icon as a string
     * @return string path to the image (web visible) that was created or pulled together
     */
    getLogoSize(height, width) {
      let fileName = HAXCMS.staticCache(__FUNCTION__ + height + width);
      if (!(fileName)) {
        // if no logo, just bail with an easy standard one
        if (!(this.manifest.metadata.site.logo) || ((this.manifest.metadata.site) && (this.manifest.metadata.site.logo == '' || this.manifest.metadata.site.logo == null || this.manifest.metadata.site.logo == "null"))) {
            fileName = 'assets/icon-' + height + 'x' + width + '.png';
        }
        else {
          // ensure this path exists otherwise let's create it on the fly
          let path = HAXCMS.HAXCMS_ROOT + '/' + HAXCMS.sitesDirectory + '/' + this.name + '/';
          fileName = this.manifest.metadata.site.logo.replace('files/', 'files/haxcms-managed/' + height + 'x' + width + '-');
          if (fs.pathExistsSync(path + this.manifest.metadata.site.logo) &&
              fs.lstatSync(path + this.manifest.metadata.site.logo).isFile() && 
              !fs.pathExistsSync(path + fileName)) {
              fs.mkdir(path + 'files/haxcms-managed');
              image = new ImageResize(path + this.manifest.metadata.site.logo);
              image.crop(height, width)
              .save(path + fileName);
          }
        }
      }
      return fileName;
    }
    /**
     * Load field schema for a page
     * Field cascade always follows Core + Deploy + Theme + Site
     * Anything downstream can always override upstream but no one can remove fields
     */
    async loadNodeFieldSchema(page)
    {
        let fields = {
            'configure':{},
            'advanced':{}
        };
        // load core fields
        // it may seem silly but we seek to not brick any usecase so if this file is gone.. don't die
        if (fs.pathExistsSync(HAXCMS.coreConfigPath + 'nodeFields.json') &&
            fs.lstatSync(HAXCMS.coreConfigPath + 'nodeFields.json').isFile()) {
            let coreFields = json_decode(
              await fs.readFileSync(
                    HAXCMS.coreConfigPath + 'nodeFields.json',
                    {encoding:'utf8', flag:'r'}
                )
            );
            let themes = {};
            let hThemes = HAXCMS.getThemes();
            for (var key in hThemes) {
              let item = hThemes[key];
              themes[key] = item.name;
              themes['key'] = key;
            }
            // this needs to be set dynamically
              for (var key in coreFields.advanced) {
                let item = coreFields.advanced[key];
                if (item.property === 'theme') {
                  coreFields.advanced[key].options = themes;
                }
            }
            // CORE fields
            if ((coreFields.configure)) {
              for (var key in coreFields.configure) {
                let item = coreFields.configure[key];
                // edge case for pathauto
                if (item.property == 'location' && (this.manifest.metadata.site.settings.pathauto) && this.manifest.metadata.site.settings.pathauto) {
                  // skip this core field if we have pathauto on
                  item.required = false;
                  item.disabled = true;
                }
                fields['configure'].push(item);
              }
            }
            if (coreFields.advanced) {
              for (var key in coreFields.advanced) {
                let item = coreFields.advanced[key];
                fields['advanced'].push(item);
              }
            }
        }
        // fields can live globally in config
        if ((HAXCMS.config.node.fields)) {
            if ((HAXCMS.config.node.fields.configure)) {
                for (var key in HAXCMS.config.node.fields.configure) {
                    fields['configure'].push(HAXCMS.config.node.fields.configure[key]);
                }
            }
            if ((HAXCMS.config.node.fields.advanced)) {
              for (var key in HAXCMS.config.node.fields.advanced) {
                fields['advanced'].push(HAXCMS.config.node.fields.advanced[key]);
              }
            }
        }
        // fields can live in the theme
        if (
            (this.manifest.metadata.theme.fields) &&
            fs.pathExistsSync(HAXCMS.HAXCMS_ROOT +
              '/build/es6/node_modules/' +
              this.manifest.metadata.theme.fields) &&
            fs.lstatSync(
                HAXCMS.HAXCMS_ROOT +
                    '/build/es6/node_modules/' +
                    this.manifest.metadata.theme.fields
            ).isFile()
        ) {
            // @todo think of how to make this less brittle
            // not a fan of pegging loading this definition to our file system's publishing structure
            themeFields = json_decode(
              await fs.readFileSync(
                    HAXCMS.HAXCMS_ROOT +
                        '/build/es6/node_modules/' +
                        this.manifest.metadata.theme.fields,
                        {encoding:'utf8', flag:'r'}
                )
            );
            if ((themeFields.configure)) {
                for (var key in themeFields.configure) {
                  fields['configure'].push(themeFields.configure[key]);
                }
            }
            if ((themeFields.advanced)) {
              for (var key in themeFields.advanced) {
                fields['advanced'].push(themeFields.advanced[key]);
              }
            }
        }
        // fields can live in the site itself
        if (this.manifest.metadata.node.fields) {
            if (this.manifest.metadata.node.fields.configure) {
                for (var key in this.manifest.metadata.node.fields.configure) {
                    fields['configure'].push(this.manifest.metadata.node.fields.configure[key]);
                }
            }
            if (this.manifest.metadata.node.fields.advanced) {
              for (var key in this.manifest.metadata.node.fields.advanced) {
                fields['advanced'].push(this.manifest.metadata.node.fields.advanced[key]);
              }
            }
        }
        // core values that live outside of the fields area
        let values = {
          'title': page.title,
          'location': page.location.replace('pages/','').replace('/index.html', ''),
          'description':page.description,
          'created':((page.metadata.created) ? page.metadata.created : 54),
          'published':((page.metadata.published) ? page.metadata.published : true),
        };
        // now get the field data from the page
        if ((page.metadata.fields)) {
          for (var key in page.metadata.fields) {
            let item = page.metadata.fields[key];
            if (key == 'theme') {
              values[key] = item['key'];
            } else {
              values[key] = item;
            }
          }
        }
        // response as schema and values
        response = {};
        response.haxSchema = fields;
        response.values = values;
        return response;
    }
    /**
     * License data for common open license
     */
    getLicenseData(type = 'select')
    {
        let list = {
            "by":{
                'name':"Creative Commons: Attribution",
                'link':"https://creativecommons.org/licenses/by/4.0/",
                'image':"https://i.creativecommons.org/l/by/4.0/88x31.png"
            },
            "by-sa":{
                'name':"Creative Commons: Attribution Share a like",
                'link':"https://creativecommons.org/licenses/by-sa/4.0/",
                'image':"https://i.creativecommons.org/l/by-sa/4.0/88x31.png"
            },
            "by-nd":{
                'name':"Creative Commons: Attribution No derivatives",
                'link':"https://creativecommons.org/licenses/by-nd/4.0/",
                'image':"https://i.creativecommons.org/l/by-nd/4.0/88x31.png"
            },
            "by-nc":{
                'name':"Creative Commons: Attribution non-commercial",
                'link':"https://creativecommons.org/licenses/by-nc/4.0/",
                'image':"https://i.creativecommons.org/l/by-nc/4.0/88x31.png"
            },
            "by-nc-sa":{
                'name' :
                    "Creative Commons: Attribution non-commercial share a like",
                'link':"https://creativecommons.org/licenses/by-nc-sa/4.0/",
                'image' :
                    "https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png"
            },
            "by-nc-nd":{
                'name' :
                    "Creative Commons: Attribution Non-commercial No derivatives",
                'link':"https://creativecommons.org/licenses/by-nc-nd/4.0/",
                'image' :
                    "https://i.creativecommons.org/l/by-nc-nd/4.0/88x31.png"
            }
        };
        let data = {};
        if (type == 'select') {
            for (var key in list) {
              data[key] = list[key]['name'];
            }
        }
        else {
            data = list;
        }
        return data;
    }
    /**
     * Update page in the manifest list of items. useful if updating some
     * data about an existing entry.
     * @return JSONOutlineSchemaItem or false
     */
    async updateNode(page)
    {
      for (var key in this.manifest.items) {
        let item = this.manifest.items[key];
        if (item.id === page.id) {
          this.manifest.items[key] = page;
          await this.manifest.save(false);
          await this.updateAlternateFormats();
          return page;
        }
      }
      return false;
    }
    /**
     * Delete a page from the manifest
     * @return JSONOutlineSchemaItem or false
     */
    async deleteNode(page)
    {
          for (var key in this.manifest.items) {
            let item = this.manifest.items[key];
            if (item.id === page.id) {
                delete this.manifest.items[key];
                await this.manifest.save(false);
                await this.updateAlternateFormats();
                return true;
            }
        }
        return false;
    }
    /**
     * Change the directory this site is located in
     */
    async changeName(newName)
    {
        newName = newName.replace('./', '').replace('../', '');
        // attempt to shift it on the file system
        if (newName != this.manifest.metadata.site.name) {
            this.manifest.metadata.site.name = newName;
            return await fs.rename(this.manifest.metadata.site.name, newName);
        }
    }
    /**
     * Test and ensure the name being returned is a slug currently unused
     */
    getUniqueSlugName(slug, page = null, pathAuto = false)
    {
      let rSlug = slug;
      // check for pathauto setting and this having a parent
      if (page != null && page.parent != null && page.parent != '' && pathAuto) {
        let item = page;
        let pieces = [slug];
        while (item = this.manifest.getItemById(item.parent)) {
            tmp = explode('/', item.slug);
            array_unshift(pieces, tmp.pop());
        }
        slug = implode('/', pieces);
        rSlug = slug;
      }
      let loop = 0;
      let ready = false;
      // while not ready, keep checking
      while (ready) {
        ready = true;
        // loop through items
        for (var key in this.manifest.items) {
          let item = this.manifest.items[key];
          // if our slug matches an existing
          if (rSlug == item.slug) {
            // if we have a page, and it matches that, bail out cause we have it already
            if (page != null && item.id == page.id) {
              return rSlug;
            }
            else {
              // increment the number
              loop++;
              // append to the new slug
              rSlug = slug + '-' + loop;
              // force a new test
              ready = false;
            }
          }
        }
      }
      return rSlug;
    }
    /**
     * Recursive copy to rename high level but copy all files
     */
    async recurseCopy(src, dst, skip = [])
    {
      await fs.copySync(src, dst);
    }
}
module.exports = HAXCMS;