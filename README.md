# Socle Symfony Definima

Le socle de symfony 3.4 bsique pour développer chez Definima.

Les bundles chargé dans la master sont les suivants :

- Assetic (Pour la compilation SCSS automatique en dev)
- Compass (Pour les mixins notemment et la compilation à la volée)
- CoreSphere Bundle (pour lancer des commandes symfony depuis le navigateur)
- FosUser/PUGX Multi user avec un début de hierarchie (Admin/User)
- Gedmo (Extension de doctrine)

Le AppBundle contient de quoi commencer en scss (Architecture de base avec les mixins de compass)

Liste des bibliotèques inclusent de base dans le layout du AppBundle:

##CSS :
- Bootstrap 4.1 (Avec l'ajout des col-fluid de definima)

##JS :
- Datatable
- Jquery
- Jquery ui
- jquery validate (Avec localisation FR)
- Select2
- Summernote (Avec plugin de nettoyage)