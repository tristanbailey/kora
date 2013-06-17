
/**
 * Product:     Pre-Order
 * Package:     Aitoc_Aitpreorder_1.1.23_332852
 * Purchase ID: n/a
 * Generated:   2012-07-19 20:16:28
 * File path:   skin/frontend/default/default/js/aitoc/aitpreorder/preorder.js
 * Copyright:   (c) 2012 AITOC, Inc.
 */
/**************************** CONFIGURABLE PRODUCT **************************/
if(typeof Product != 'undefined'){
    Product.ConfigPreorder = Class.create();
    Product.ConfigPreorder.prototype = {
        initialize: function(config,preorders){
            this.config     = config;
            this.taxConfig  = this.config.taxConfig;
            this.settings   = $$('.super-attribute-select');
            this.state      = new Hash();
            this.priceTemplate = new Template(this.config.template);
            this.prices     = config.prices;
            this.configpreorders=preorders;
            this.preorders=this.configpreorders.preorder;
            this.bufstock="";

            this.settings.each(function(element){
                Event.observe(element, 'change', this.configure.bind(this))
            }.bind(this));

        

        },

        configure: function(event){
            var element = Event.element(event);
            this.configureElement(element);
        },

        configureElement : function(element) {
            
            
            if(this.settings.length>0)
            {
                var first=1;
                var res={};
                for(var i=0;i<=this.settings.length-1;i++)
                {
                    var attributeId = this.settings[i].id.replace(/[a-z]*/, '');
                    if((first==1)&&(this.settings[i].selectedIndex>0))
                    {

                        var res=this.config['attributes'][attributeId]['options'][this.settings[i].selectedIndex-1]['products'];
                 
                    }
                    else
                    {    if(this.settings[i].selectedIndex>0)
                        {
                            
                            res=res.intersect(this.config['attributes'][attributeId]['options'][this.settings[i].selectedIndex-1]['products']);
                        }
                    }    
                        first=0;
                }
                
     
                var el=$('saypreorder');
                var masAvail=$$('.availability');
                var elmas=masAvail[0];
                var childEl = elmas.childElements();
                var spanEl = childEl[0];
               
                if(res.length==1)
                {
                    var descr=this.preorders['descript'][res[0]];
                    $('canBePreorder').update('<p class="required">*'+descr+'</p>');
                    spanEl.update(descr);
                }
                else 
                {
                    $('canBePreorder').update('');  
                }
            }    
                
        }
     
    }

}