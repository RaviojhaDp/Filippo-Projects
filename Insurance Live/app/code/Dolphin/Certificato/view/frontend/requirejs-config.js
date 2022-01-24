var config = {
   map: {
       "*": {
           calMethod: "Dolphin_Certificato/js/caldValidationRule",
           emailMethod: "Dolphin_Certificato/js/emailValidationRule",
           emailMethodit: "Dolphin_Certificato/js/emailValidationRuleit",
           eqpMethod: "Dolphin_Certificato/js/eqpValidationRule",
           iselectedMethod: "Dolphin_Certificato/js/iselectedValidationRule",
           purchasedateMethod: "Dolphin_Certificato/js/purchasedateValidationRule",
           dobMethod: "Dolphin_Certificato/js/dobValidationRule.js",
           zipMethod: "Dolphin_Certificato/js/zipValidationRule.js"
          }
       },
       
    paths: {            
         'steps': "Dolphin_Insurance/js/jquery.steps"
      },

	shim: {
	        'steps': {
	            deps: ['jquery']
	        }
	    }
       
   
};