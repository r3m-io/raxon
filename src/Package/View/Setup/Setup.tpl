{{R3M}}
{{$register = Package.R3m.Io.Raxon:Init:register()}}
{{if(!is.empty($register))}}
{{Package.R3m.Io.Raxon:Import:role.system()}}
{{$options = options()}}
{{Package.R3m.Io.Raxon:Main:system.config($options)}}
{{/if}}