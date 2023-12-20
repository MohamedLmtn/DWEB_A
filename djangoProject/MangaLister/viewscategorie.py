from django.shortcuts import render, redirect
from django.http import HttpResponse
from MangaLister.models import Category
from MangaLister.models import Manga
from django.views.decorators.csrf import csrf_exempt
from MangaLister.models import MangaCategory




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
