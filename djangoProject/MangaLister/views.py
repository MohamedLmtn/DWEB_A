from django.shortcuts import render, redirect
from django.http import HttpResponse
from MangaLister.models import Category
from MangaLister.models import Manga
from django.views.decorators.csrf import csrf_exempt
from MangaLister.models import MangaCategory


def htmlbodybase(request):
    context = {'listallmanga': Manga.objects.all(),
               'listcategorie': Category.objects.all()}

    return render(request, 'index.html', context)


def htmladdMangaDetails(request):
    if request.method == 'POST':
        MangaCat = request.POST.get('categ', '')
        mangas_filtered = Manga.objects.filter(mangacategory__category__name=MangaCat)

        context = {
            'listallmanga': mangas_filtered,
            'listcategorie': Category.objects.all()
        }

    return render(request, 'index.html', context)


def adminpage(request):
    lstcat = ""
    for Cat in Category.objects.all():
        lstcat += f"{Cat.id}.{Cat.name} "

    return render(request, 'admintraitement.html',
                  {'listallmanga': Manga.objects.all(), 'listcategorie': Category.objects.all(), "catString": lstcat})


def traitementadmin(request, parametre=None):
    if parametre is not None:
        Manga.objects.filter(id=parametre).delete()
    elif request.POST.get('typetraitement') == 'Update':
        idmanga = request.POST.get('idmanga', '')
        try:
            manga = Manga.objects.get(id=idmanga)
            new_title = request.POST.get('nommanga', '')
            new_author = request.POST.get('nomautheur', '')
            catmanga = request.POST.get('catmanga', '')

            if manga.title != new_title:
                manga.title = new_title
            if manga.author != new_author:
                manga.author = new_author

            categoriemanga = MangaCategory.objects.get(manga_id=idmanga)
            if categoriemanga.category_id != catmanga:
                categoriemanga.category_id = catmanga
            categoriemanga.save()
            manga.save()
        except Manga.DoesNotExist:
            pass
    elif request.POST.get('typetraitement') == 'Add':
        catmanga = request.POST.get('catmanga', '')
        new_title = request.POST.get('nommanga', '')
        new_author = request.POST.get('nomautheur', '')

        if new_title and new_author:
            nvmanga = Manga.objects.create(title=new_title, author=new_author)
            nvmanga.save()
        if catmanga:
            nvcategorie = MangaCategory.objects.create(category_id=catmanga, manga_id=nvmanga.id)
            nvcategorie.save()
    return redirect('/home/')


def traitementadmincat(request, parametre=None):
    if parametre is not None:
        MangaCategory.objects.filter(category_id=parametre).delete()
        Category.objects.filter(id=parametre).delete()

    elif request.POST.get("typetraitementcat") == 'Update':
        idmanga = request.POST.get('idcat')
        cat = Category.objects.get(id=idmanga)
        nvcatname = request.POST.get('namecat')
        if cat.name != nvcatname:
            cat.name = nvcatname
            cat.save()
    elif request.POST.get("typetraitementcat") == 'Add':
        namecat = request.POST.get('nomcat')
        nvcat = Category.objects.create(name=namecat)
        nvcat.save()
    return redirect('/home/')
